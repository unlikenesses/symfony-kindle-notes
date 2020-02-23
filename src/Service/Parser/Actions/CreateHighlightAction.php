<?php

namespace App\Service\Parser\Actions;

use App\ValueObject\Note;
use App\ValueObject\BookList;
use App\ValueObject\NoteMetadata;

class CreateHighlightAction implements ActionInterface
{
    /**
     * @var NoteMetadata
     */
    private $noteMetadata;

    public function __construct(NoteMetadata $noteMetadata)
    {
        $this->noteMetadata = $noteMetadata;
    }

    public function execute(BookList $bookList): BookList
    {
        $note = Note::asHighlight($this->noteMetadata);
        $bookList->addNote($note);

        return $bookList;
    }
}