<?php

namespace App\Service;

use App\ValueObject\Author;
use App\ValueObject\BookList;
use App\ValueObject\FileLine;
use App\ValueObject\NoteMetadata;

interface ParserInterface
{
    public function parseLine(FileLine $line): void;

    public function parseTitleString(string $titleString): array;

    public function parseAuthor(string $author): Author;

    public function parseMeta(string $meta): NoteMetadata;

    public function getBookList(): BookList;
}
