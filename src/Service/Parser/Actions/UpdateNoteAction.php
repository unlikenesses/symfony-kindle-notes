<?php

namespace App\Service\Parser\Actions;

use App\ValueObject\BookList;

class UpdateNoteAction implements ActionInterface
{
    /**
     * @var string
     */
    private $note;

    public function __construct(string $note)
    {
        $this->note = $note;
    }

    public function execute(BookList $bookList): BookList
    {
        $bookList->updateNote($this->note);

        return $bookList;
    }
}