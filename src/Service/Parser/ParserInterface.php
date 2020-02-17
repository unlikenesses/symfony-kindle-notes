<?php

namespace App\Service\Parser;

use App\ValueObject\BookList;
use App\ValueObject\FileLine;

interface ParserInterface
{
    public function parseLine(FileLine $line): void;

    public function getBookList(): BookList;
}
