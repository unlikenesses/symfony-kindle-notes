<?php

namespace App\Tests\Functional\API;

use App\Entity\Book;
use App\DataFixtures\BookNoteFixtures;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\Traits\HasAPIInteraction;

class BookAPITest extends FunctionalTestCase
{
    use HasAPIInteraction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            BookNoteFixtures::class,
        ]);
    }

    public function testDeletingABookSoftDeletesIt()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Delete a book
        $bookToDelete = $books[0]->id;
        $response = $this->deleteBook($bookToDelete);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $books = $this->getBooks();
        $this->assertCount(1, $books);
        // Check the book still exists in the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Check its softDeleted column is set
        $this->assertNotNull($book->getDeletedAt());
    }

    public function testNotPossibleToSoftDeleteABookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $note] = $this->createBook($secondUser);
        $this->loginUser();
        // Check we can not soft-delete the book
        $this->deleteBook($secondUsersBook->getId());
        $this->assertResponseStatusCodeSame(500);
        // Check its softDeleted column is still null
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($secondUsersBook->getId());
        $this->assertNotNull($book);
        $this->assertNull($book->getDeletedAt());
    }

    public function testPermaDeletingABookRemovesItFromDatabase()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Delete a book
        $bookToDelete = $books[0]->id;
        $response = $this->deleteBook($bookToDelete);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $books = $this->getBooks();
        $this->assertCount(1, $books);
        // Check the book still exists in the database
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Now perma-delete it
        $response = $this->permaDeleteBook([$bookToDelete]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check the book has been removed from the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNull($book);
    }

    public function testPermaDeletingABookRemovesItsNotesFromDatabase()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Test book's notes
        $bookToDelete = $books[0]->id;
        $notes = $this->getNotes($bookToDelete);
        $this->assertCount(2, $notes);
        // Delete a book
        $response = $this->deleteBook($bookToDelete);
        $this->assertResponseStatusCodeSame(200);
        // Check the book still exists in the database
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Now perma-delete it
        $response = $this->permaDeleteBook([$bookToDelete]);
        $this->assertResponseStatusCodeSame(200);
        // Check the book has been removed from the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNull($book);
        // Check the notes have been removed from the database
        $notes = $this->getNotes($bookToDelete);
        $this->assertNull($notes);
    }

    public function testNotPossibleToPermaDeleteABookIfItIsNotSoftDeleted()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Perma-delete a book
        $bookToDelete = $books[0]->id;
        $this->permaDeleteBook([$bookToDelete]);
        $this->assertResponseStatusCodeSame(500);
        // Check the book still exists in the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
    }

    public function testNotPossibleToPermaDeleteABookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $note] = $this->createBook($secondUser, true);
        $this->loginUser();
        // Check we can not perma-delete the book
        $this->permaDeleteBook([$secondUsersBook->getId()]);
        $this->assertResponseStatusCodeSame(500);
        // Check it is still in the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($secondUsersBook->getId());
        $this->assertNotNull($book);
    }

    public function testRestoringABookMakesItAvailableAgain()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Delete a book
        $bookToDelete = $books[0]->id;
        $response = $this->deleteBook($bookToDelete);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $books = $this->getBooks();
        $this->assertCount(1, $books);
        // Check the book still exists in the database
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Now restore it
        $response = $this->restoreBook([$bookToDelete]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('success', $response->message);
        // Check the book is available again
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNull($book->getDeletedAt());
        $books = $this->getBooks();
        $this->assertCount(2, $books);
    }

    public function testNotPossibleToRestoreABookIfItIsNotSoftDeleted()
    {
        $this->loginUser();
        // Get books
        $books = $this->getBooks();
        $this->assertCount(2, $books);
        // Check we cannot restore a book
        $bookToDelete = $books[0]->id;
        $this->restoreBook([$bookToDelete]);
        $this->assertResponseStatusCodeSame(500);
    }

    public function testNotPossibleToRestoreABookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $note] = $this->createBook($secondUser, true);
        $this->loginUser();
        // Check we can not restore the book
        $this->restoreBook([$secondUsersBook->getId()]);
        $this->assertResponseStatusCodeSame(500);
    }

    public function testCanUpdateABooksTitle()
    {
        $this->loginUser();
        $books = $this->getBooks();
        $bookToUpdate = $books[0];
        $newTitle = 'this is the new title';
        $this->updateBookTitle($bookToUpdate->id, $newTitle);
        $this->assertResponseStatusCodeSame(200);
        $books = $this->getBooks();
        $book = $books[0];
        $this->assertSame($newTitle, $book->title);
    }

    public function testCannotUpdateTitleOfBookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $note] = $this->createBook($secondUser);
        $this->loginUser();
        $newTitle = 'this is the new title';
        $this->updateBookTitle($secondUsersBook->getId(), $newTitle);
        $this->assertResponseStatusCodeSame(500);
    }


    public function testCanUpdateABooksAuthor()
    {
        $this->loginUser();
        $books = $this->getBooks();
        $bookToUpdate = $books[0];
        $newFirstName = 'Felix';
        $newLastName = 'Holt';
        $this->updateBookAuthor($bookToUpdate->id, $newFirstName, $newLastName);
        $this->assertResponseStatusCodeSame(200);
        $books = $this->getBooks();
        $book = $books[0];
        $this->assertSame($newFirstName, $book->firstName);
        $this->assertSame($newLastName, $book->lastName);
    }

    public function testCannotUpdateAuthorOfBookYouDoNotOwn()
    {
        $secondUser = $this->createUser();
        [$secondUsersBook, $note] = $this->createBook($secondUser);
        $this->loginUser();
        $newFirstName = 'Felix';
        $newLastName = 'Holt';
        $this->updateBookAuthor($secondUsersBook->getId(), $newFirstName, $newLastName);
        $this->assertResponseStatusCodeSame(500);
    }
}