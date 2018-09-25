<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 23/09/18
 * Time: 21:32
 */

namespace AppBundle\Controller;

use AppBundle\Entity\UserChoice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MovieController
 * @package AppBundle\Controller
 */
class MovieController extends Controller
{

    /**
     * @param string $movieId
     *
     * @Route("/movies/best_of", name="blabla_movie.movie.best_of")
     *
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function bestMovieAction()
    {
        try {
            $movieId = $this->get('doctrine')->getRepository(UserChoice::class)->getBestMovie();
            $movie = $this->get('blabla_movie.movie.client')->fetch($movieId);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($movie->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param string $movieId
     *
     * @Route("/movies/{movieId}", name="blabla_movie.movie.users")
     *
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function listMovieUsersAction(string $movieId)
    {
        try {
            $this->get('blabla_movie.movie.client')->fetchById($movieId);
            $userChoices = $this->get('doctrine')->getRepository(UserChoice::class)->findAllBy(['movie' => $movieId]);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        $users = [];
        foreach ($userChoices as $userChoice) {
            $users[] = $userChoice->getUser()->jsonSerialize();
        }

        return new JsonResponse($users, Response::HTTP_CREATED);
    }
}