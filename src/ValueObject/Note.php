<?php

namespace App\ValueObject;

class Note
{
    /**
     * @var NoteMetadata
     */
    private $meta;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $highlight;

    public function getMeta(): ?NoteMetadata
    {
        return $this->meta;
    }

    public function setMeta(NoteMetadata $metadata): void
    {
        $this->meta = $metadata;
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