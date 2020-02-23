<?php

namespace App\Service\Parser\Paperwhite;

use App\ValueObject\FileLine;
use App\ValueObject\NoteMetadata;
use App\Service\Parser\LineReaderInterface;
use App\Service\Parser\Actions\EmptyAction;
use App\Service\Parser\Actions\ActionInterface;
use App\Service\Parser\Actions\CreateNoteAction;
use App\Service\Parser\Actions\UpdateNoteAction;
use App\Service\Parser\Actions\CompleteNoteAction;
use App\Service\Parser\Actions\CreateHighlightAction;

class PaperwhiteLineReader implements LineReaderInterface
{
    const SEPARATOR = '==========';
    const HIGHLIGHT_STRING = 'your highlight';
    const NOTE_STRING = 'your note';

    public function classifyLine(FileLine $line): ActionInterface
    {
        if ($line->isEmpty()) {
            return new EmptyAction();
        }

        if ($line->equals(self::SEPARATOR)) {
            return new CompleteNoteAction();
        }

        if ($line->contains(self::HIGHLIGHT_STRING)) {
            $noteMetadata = new NoteMetadata($line->getLine());
            return new CreateHighlightAction($noteMetadata);
        }

        if ($line->contains(self::NOTE_STRING)) {
            $noteMetadata = new NoteMetadata($line->getLine());
            return new CreateNoteAction($noteMetadata);
        }

        return new UpdateNoteAction($line->getLine());
    }
}
