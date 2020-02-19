<?php

namespace App\Service\Parser;

use App\ValueObject\Author;

interface TitleStringParserInterface
{
    public function parse(string $titleString): void;

    public function getTitle(): string;

    public function getAuthor(): Author;
}