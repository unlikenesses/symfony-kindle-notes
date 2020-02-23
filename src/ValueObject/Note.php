<?php

namespace App\ValueObject;

class Note
{
    const TYPE_HIGHLIGHT = 1;
    const TYPE_NOTE = 2;

    /**
     * @var int
     */
    private $type;

    /**
     * @var NoteMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $text;

    private function __construct(int $type, NoteMetadata $metadata)
    {
        $this->type = $type;
        $this->metadata = $metadata;
    }

    public static function asHighlight(NoteMetadata $metadata)
    {
        return new Note(self::TYPE_HIGHLIGHT, $metadata);
    }

    public static function asNote(NoteMetadata $metadata)
    {
        return new Note(self::TYPE_NOTE, $metadata);
    }

    public function getMetadata(): ?NoteMetadata
    {
        return $this->metadata;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = trim($text);
    }
}