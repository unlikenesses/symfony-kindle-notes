<?php

namespace App\Service\Parser\Actions;

use App\ValueObject\BookList;

class EmptyAction implements ActionInterface
{
    public function execute(BookList $bookList): BookList
    {
        return $bookList;
    }
}