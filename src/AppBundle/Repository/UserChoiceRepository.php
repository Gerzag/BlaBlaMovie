<?php

namespace AppBundle\Repository;

use AppBundle\Client\OmdbAPIClient;
use AppBundle\Entity\Movie;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserChoiceRepository
 * @package AppBundle\Repository
 */
class UserChoiceRepository extends EntityRepository
{
    /** @var OmdbAPIClient */
    protected $client;

    /**
     * @param OmdbAPIClient $client
     */
    public function setClient(OmdbAPIClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return Movie
     */
    public function getBestMovie()
    {
        $movie = $this
            ->createQueryBuilder('uc')
            ->select('uc.movie, count(uc.user) as nbUsers')
            ->groupBy('uc.movie')
            ->orderBy('nbUsers', 'desc')
            ->getQuery()
            ->setMaxResults(1)
            ->execute();

        return $this->client->fetchById($movie->getId());
    }
}
