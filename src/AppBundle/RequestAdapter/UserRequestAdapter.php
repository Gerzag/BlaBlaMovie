<?php

namespace AppBundle\RequestAdapter;

use AppBundle\Entity\User;
use AppBundle\Exception\UsernameAllreadyExistsException;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
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
     */
    public function getUser(Request $request)
    {
        $user = new User();
        $this->checkRequest($request->request);
        $homonyms = $this->entityManager->getRepository(User::class)->findBy(['username' => $request->request->get('username')]);
        if (empty($homonyms)) {

        } else {
            throw new UsernameAllreadyExistsException($request->request->get('username'));
        }

        return $user;
    }

    /**
     * @param ParameterBag $bag
     */
    private function checkRequest(ParameterBag $bag)
    {
        if (!$bag->get('username')) {
            throw new InvalidArgumentException("Username not provided.");
        }
        if (!$bag->get('email')) {
            throw new InvalidArgumentException("Email not provided.");
        }
    }
}