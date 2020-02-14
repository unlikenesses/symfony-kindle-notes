<?php

namespace App\Service;

use App\ValueObject\Author;
use App\ValueObject\NoteMetadata;

interface ParserInterface
{
    public function parseFile(string $filename): array;

    public function parseTitleString(string $titleString): array;

    public function parseAuthor(string $author): Author;

    public function parseMeta(string $meta): NoteMetadata;
}
