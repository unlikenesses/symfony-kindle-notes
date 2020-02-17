<?php

namespace App\ValueObject;

class Note
{
    /**
     * @var NoteMetadata
     */
    private $metadata;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $highlight;

    public function getMetadata(): ?NoteMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(NoteMetadata $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getHighlight(): ?string
    {
        return $this->highlight;
    }

    public function setHighlight(string $highlight): void
    {
        $this->highlight = trim($highlight);
    }
}