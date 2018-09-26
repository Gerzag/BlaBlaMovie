<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 23/09/18
 * Time: 21:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserChoice;
use AppBundle\RequestAdapter\UserRequestAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRequestAdapter     $requestAdapter
     * @param Request                $request
     *
     * @Method("POST")
     *
     * @Route("/users", name="blabla_movie.user.create")
     *
     * @return JsonResponse
     */
    public function createUserAction(EntityManagerInterface $entityManager, UserRequestAdapter $requestAdapter, Request $request)
    {
        try {
            /** @var User $user */
            $user = $requestAdapter->getUser($request);
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user->jsonSerialize());
    }

    /**
     * @param EntityManagerInterface $entityManager,
     * @param Request                $request
     * @param int                    $userId
     *
     * @Route("/users/{userId}/movie", name="blabla_movie.user.choice", requirements={"userId": "\d+"})
     *
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function postUserChoiceAction(EntityManagerInterface $entityManager,  Request $request, int $userId)
    {
        try {
            /** @var User $user */
            $user = $entityManager->getRepository(User::class)->find($userId);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
        /** @var UserChoice $userChoice */
        $userChoice = $this->get('blabla_movie.movie.request_adapter')->getUserChoice($request);
        $entityManager->persist($userChoice);
        $userChoice->setUser($user);
        $entityManager->flush();

        return new JsonResponse($userChoice->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param int                    $userId
     * @param int                    $choiceId
     *
     * @Route("/users/{userId}/movie/{choiceId}", name="blabla_movie.movie.delete", requirements={"userId": "\d+", "choiceId": "\d+"})
     *
     * @Method("DELETE")
     *
     * @return JsonResponse
     */
    public function deleteUserChoiceAction(EntityManagerInterface $entityManager, int $userId, int $choiceId)
    {
        try {
            $entityManager->getRepository(User::class)->find($userId);
            $choice = $entityManager->getRepository(UserChoice::class)->find($choiceId);
            $entityManager->remove($choice);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse('Choix supprimé.', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param int                    $userId
     *
     * @Route("/users/{userId}/movies", name="blabla_movie.user.list", requirements={"userId": "\d+"})
     *
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function listUserChoiceAction(EntityManagerInterface $entityManager, int $userId)
    {
        try {
            $user = $entityManager->getRepository(User::class)->find($userId);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        $userChoices = $entityManager->getRepository(UserChoice::class)->findAllBy(['user' => $user]);
        //@TODO
        //$userChoicesArray = $manager->toArray($userChoices);

        //return new JsonResponse($userChoicesArray, Response::HTTP_CREATED);
    }
}
