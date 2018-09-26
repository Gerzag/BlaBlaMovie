<?php

namespace AppBundle\RequestAdapter;

use AppBundle\Client\OmdbAPIClient;
use AppBundle\Entity\User;
use AppBundle\Entity\UserChoice;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MovieRequestAdapter
 * @package AppBundle\RequestAdapter
 */
class MovieRequestAdapter
{
    /** @var OmdbAPIClient $client */
    protected $client;

    /**
     * MovieRequestAdapter constructor.
     * @param OmdbAPIClient $client
     */
    public function __construct(OmdbAPIClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return UserChoice
     */
    public function getUserChoice(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        if (!empty($data['movieTitle'])) {
            $movie = $this->client->fetchByName($data['movieTitle']);
        } elseif (!empty($data['movieId'])) {
            $movie = $this->client->fetchById($data['movieId']);
        } else {
            throw new \InvalidArgumentException('Either movieTitle or movieId is required.');
        }
        $userChoice = new UserChoice();
        $userChoice->setMovie($movie->getId());

        return $userChoice;
    }
}