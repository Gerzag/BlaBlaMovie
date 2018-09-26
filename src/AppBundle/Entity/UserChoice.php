<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserChoice
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserChoiceRepository")
 */
class UserChoice
{
    /**
     * @var int
     *
     * @ORM\Id()
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="choices")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $movie;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getMovie(): string
    {
        return $this->movie;
    }

    /**
     * @param string $movie
     */
    public function setMovie(string $movie): void
    {
        $this->movie = $movie;
    }
}
