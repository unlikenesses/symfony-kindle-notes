<?php

namespace App\Service;

interface ParserInterface
{
    public function parseFile(string $filename): array;

    public function parseTitleString(string $titleString): array;

    public function parseAuthor(string $author): array;

    public function parseMeta(string $meta): array;
}