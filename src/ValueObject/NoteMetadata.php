<?php

namespace App\ValueObject;

class NoteMetadata
{
    /**
     * @var string
     */
    private $page;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $date;

    /**
     * @return string
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}