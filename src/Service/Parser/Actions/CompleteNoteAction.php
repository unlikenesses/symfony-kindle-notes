<?php

namespace App\Service\Parser\Actions;

use App\ValueObject\BookList;

class CompleteNoteAction implements ActionInterface
{
    public function execute(BookList $bookList): BookList
    {
        $bookList->completeNote();

        return $bookList;
    }
}