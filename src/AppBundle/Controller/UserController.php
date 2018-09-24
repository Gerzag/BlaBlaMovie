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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @Method("POST")
     *
     * @Route("/users", name="blabla_movie.user.create")
     *
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        try {
            $user = $this->get('blabla_movie.user.request_adapter')->getUser($request);
            $this->get('blabla_movie.user.manager')->persist($user);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($user->jsonSerialize());
    }

    /**
     * @param Request $request
     * @param int $userId
     *
     * @Route("/users/{userId}/movie", name="blabla_movie.user.choice", requirements={"userId": "\d+"})
     *
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function postUserChoiceAction(Request $request, int $userId)
    {
        $manager =  $this->get('blabla_movie.movie.manager');
        try {
            $this->get('doctrine')->getRepository(User::class)->find($userId);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        $userChoice = $this->get('blabla_movie.movie.request_adapter')->getUserChoice($request);
        $manager->persist($userChoice);
        $userChoiceArray = $manager->toArray($userChoice);

        return new JsonResponse($userChoiceArray, Response::HTTP_CREATED);
    }

    /**
     * @param int $userId
     * @param int $choiceId
     *
     * @Route("/users/{userId}/movie/{choiceId}", name="blabla_movie.movie.delete", requirements={"userId": "\d+", "choiceId": "\d+"})
     *
     * @Method("DELETE")
     *
     * @return JsonResponse
     */
    public function deleteUserChoiceAction(int $userId, int $choiceId)
    {
        try {
            $this->get('doctrine')->getRepository(User::class)->find($userId);
            $choice = $this->get('doctrine')->getRepository(UserChoice::class)->find($choiceId);
            $this->get('doctrine')->remove($choice);
            $this->get('doctrine')->flush();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse('Choix supprimé.', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @param int $userId
     *
     * @Route("/users/{userId}/movies", name="blabla_movie.user.list", requirements={"userId": "\d+"})
     *
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function listUserChoiceAction(Request $request, int $userId)
    {
        $manager =  $this->get('blabla_movie.movie.manager');
        try {
            $user = $this->get('doctrine')->getRepository(User::class)->find($userId);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        $userChoices = $this->get('doctrine')->getRepository(UserChoice::class)->findAllBy(['user' => $user]);
        $userChoicesArray = $manager->toArray($userChoices);

        return new JsonResponse($userChoicesArray, Response::HTTP_CREATED);
    }
}
