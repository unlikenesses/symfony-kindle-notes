<?php

namespace App\ValueObject;

class Book
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * @var array
     */
    private $notes = [];

    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }

    public function addNote(array $note): void
    {
        $this->notes[] = $note;
    }
}

