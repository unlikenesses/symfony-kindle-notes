<?php

namespace App\ValueObject;

class Book
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * @var array<Note>
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

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }
}

