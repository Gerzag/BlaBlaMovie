<?php

namespace AppBundle\Entity;

/**
 * Class Movie
 * @package AppBundle\Entity
 */
class Movie implements \JsonSerializable
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $poster;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPoster(): string
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster(string $poster): void
    {
        $this->poster = $poster;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'poster' => $this->poster,
        ];
    }
}