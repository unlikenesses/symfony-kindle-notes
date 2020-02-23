<?php

namespace App\Service\Parser\Actions;

use App\ValueObject\BookList;

interface ActionInterface
{
    public function execute(BookList $bookList): BookList;
}