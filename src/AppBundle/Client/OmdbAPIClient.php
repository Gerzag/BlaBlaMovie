<?php

namespace AppBundle\Client;

use AppBundle\Entity\Movie;

/**
 * Class OmdbAPIClient
 * @package AppBundle\Client
 */
class OmdbAPIClient
{
    protected $url = "http://www.omdbapi.com";

    /** @var HttpClient */
    protected $client;

    /** @var string  */
    protected $apiKey;

    /**
     * OmdbAPIClient constructor.
     * @param string $apiKey
     */
    public function __construct(HttpClient $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @param $movieId
     *
     * @return Movie
     *
     * @throws \Exception
     */
    public function fetchById(string $movieId)
    {
        $response = $this->client->fetch($this->url, ['i' => $movieId, 'apikey' => $this->apiKey]);

        return $this->generateMovieFromJson($response);
    }

    /**
     * @param string $movieName
     *
     * @return Movie
     *
     * @throws \Exception
     */
    public function fetchByName(string $movieName)
    {
        $response = $this->client->fetch($this->url, ['s' => $movieName, 'apikey' => $this->apiKey]);

        return $this->generateMovieFromJson($response);
    }

    /**
     * @param $jsonResponse
     * @return Movie
     *
     * @TODO move this out of here
     */
    private function generateMovieFromJson($jsonResponse)
    {
        $movieResponse = json_decode($jsonResponse, true);
        $movie = new Movie();
        if (!empty($movieResponse) && isset($movieResponse['Search'])) {
            $movieResponse = $movieResponse['Search'][0];
        }
        $movie->setId($movieResponse['imdbID']);
        $movie->setTitle($movieResponse['Title']);
        $movie->setPoster($movieResponse['Poster']);

        return $movie;
    }
}