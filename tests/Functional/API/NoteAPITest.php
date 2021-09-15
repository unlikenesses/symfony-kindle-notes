<?php

namespace App\Tests\Functional\API;

use App\Tests\Functional\FunctionalTestCase;

class NoteAPITest extends FunctionalTestCase
{
    use HasAPIInteraction;

    public function testDeletingANoteSoftDeletesIt()
    {
        $this->loginUser();
        // Get notes
        $books = $this->getBooks();
        $bookId = $books[0]->id;
        $notes = $this->getNotes($bookId);
        $this->assertCount(2, $notes);
        // Delete a note
        $noteToDelete = $notes[0]->id;
        $response = $this->deleteNote($noteToDelete);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $notes = $this->getNotes($bookId);
        $this->assertCount(1, $notes);
        // Check the note still exists in the database
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNotNull($note);
        // Check its softDeleted column is set
        $this->assertNotNull($note->getDeletedAt());
    }
}