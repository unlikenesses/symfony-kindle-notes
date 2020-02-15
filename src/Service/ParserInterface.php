<?php

namespace App\Service;

use App\ValueObject\BookList;
use App\ValueObject\FileLine;
use App\ValueObject\NoteMetadata;

interface ParserInterface
{
    public function parseLine(FileLine $line): void;

    public function parseNoteMetadata(string $metadata): NoteMetadata;

    public function getBookList(): BookList;
}
