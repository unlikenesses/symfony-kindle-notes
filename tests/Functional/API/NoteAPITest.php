<?php

namespace App\Tests\Functional\API;

use App\DataFixtures\BookNoteFixtures;
use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\Traits\HasAPIInteraction;
use Doctrine\Common\Collections\Collection;

class NoteAPITest extends FunctionalTestCase
{
    use HasAPIInteraction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            BookNoteFixtures::class,
        ]);
    }

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
        $this->assertResponseStatusCodeSame(200);
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

    public function testNotPossibleToSoftDeleteANoteInABookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $secondUsersNote] = $this->createBook($secondUser);
        $noteId = $secondUsersNote->getId();
        $this->loginUser();
        // Check we can not soft-delete the note
        $this->deleteNote($noteId);
        $this->assertResponseStatusCodeSame(500);
        // Check its softDeleted column is still null
        $this->entityManager->clear(Note::class);
        $note = $this->noteRepository->find($noteId);
        $this->assertNotNull($note);
        $this->assertNull($note->getDeletedAt());
    }

    public function testPermaDeletingANoteRemovesItFromDatabase()
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
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $notes = $this->getNotes($bookId);
        $this->assertCount(1, $notes);
        // Check the note still exists in the database
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNotNull($note);
        // Now perma-delete it
        $response = $this->permaDeleteNote([$noteToDelete]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check the book has been removed from the database
        $this->entityManager->clear(Note::class);
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNull($note);
    }

    public function testNotPossibleToPermaDeleteANoteIfItIsNotSoftDeleted()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $bookId = $books[0]->id;
        $notes = $this->getNotes($bookId);
        // Perma-delete a note
        $noteToDelete = $notes[0]->id;
        $this->permaDeleteNote([$noteToDelete]);
        $this->assertResponseStatusCodeSame(500);
        // Check the book still exists in the database
        $this->entityManager->clear(Note::class);
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNotNull($note);
    }

    public function testNotPossibleToPermaDeleteANoteFromABookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $secondUsersNote] = $this->createBook($secondUser, false, true);
        $noteId = $secondUsersNote->getId();
        $this->loginUser();
        // Check we can not perma-delete the note
        $r = $this->permaDeleteNote([$noteId]);
        $this->assertResponseStatusCodeSame(500);
        // Check its softDeleted column is still null
        $this->entityManager->clear(Note::class);
        $note = $this->noteRepository->find($noteId);
        $this->assertNotNull($note);
    }

    public function testRestoringANoteMakesItAvailableAgain()
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
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $notes = $this->getNotes($bookId);
        $this->assertCount(1, $notes);
        // Check the note still exists in the database
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNotNull($note);
        // Check its softDeleted column is set
        $this->assertNotNull($note->getDeletedAt());
        // Now restore it
        $response = $this->restoreNote([$noteToDelete]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check the note is available again
        $this->entityManager->clear(Note::class);
        $note = $this->noteRepository->find($noteToDelete);
        $this->assertNull($note->getDeletedAt());
        $notes = $this->getNotes($bookId);
        $this->assertCount(2, $notes);
    }

    public function testNotPossibleToRestoreANoteIfItIsNotSoftDeleted()
    {
        $this->loginUser();
        // Get notes
        $books = $this->getBooks();
        $bookId = $books[0]->id;
        $notes = $this->getNotes($bookId);
        $this->assertCount(2, $notes);
        // Check we cannot restore a note
        $noteToDelete = $notes[0]->id;
        $this->restoreNote([$noteToDelete]);
        $this->assertResponseStatusCodeSame(500);
    }

    public function testNotPossibleToRestoreANoteYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $secondUsersNote] = $this->createBook($secondUser, true);
        $noteId = $secondUsersNote->getId();
        $this->loginUser();
        // Check we can not restore the note
        $this->restoreNote([$noteId]);
        $this->assertResponseStatusCodeSame(500);
    }
}