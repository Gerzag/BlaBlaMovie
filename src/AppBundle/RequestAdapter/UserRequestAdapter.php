<?php

namespace AppBundle\RequestAdapter;

use AppBundle\Entity\User;
use AppBundle\Exception\UsernameAllreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserRequestAdapter
 * @package AppBundle\RequestAdapter
 */
class UserRequestAdapter
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * UserRequestAdapter constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @return User
     *
     * @throws \InvalidArgumentException
     * @throws UsernameAllreadyExistsException
     */
    public function getUser(Request $request)
    {
        $user = new User();
        $data = json_decode(
            $request->getContent(),
            true
        );
        $this->checkRequest($data);
        $homonyms = $this->entityManager->getRepository(User::class)->findBy(['username' => $request->request->get('username')]);
        if (empty($homonyms)) {
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            try {
                $user->setBirthDate(new \DateTime($data['birthdate']));
            } catch (\Exception $e) {
                throw new \InvalidArgumentException("Invalid birthdate.");
            }
        } else {
            throw new UsernameAllreadyExistsException($request->request->get('username'));
        }

        return $user;
    }

    /**
     * @param array $data
     */
    private function checkRequest(array $data)
    {
        if (empty($data['username'])) {
            throw new \InvalidArgumentException("Username not provided.");
        }
        if (empty($data['email'])) {
            throw new \InvalidArgumentException("Email not provided.");
        }
    }
}