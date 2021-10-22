<?php

namespace App\Tests\Functional\API;

use App\Entity\Book;
use App\Entity\User;
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
        $secondUsersBook = $this->createBook($secondUser);
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
        $secondUsersBook = $this->createBook($secondUser, true);
        $this->loginUser();
        // Check we can not soft-delete the book
        $this->permaDeleteBook([$secondUsersBook->getId()]);
        $this->assertResponseStatusCodeSame(500);
        // Check it is still in the database
        $this->entityManager->clear(Book::class);
        $book = $this->bookRepository->find($secondUsersBook->getId());
        $this->assertNotNull($book);
    }

    private function createBook(User $user, bool $softDeleted = false): Book
    {
        $book = new Book();
        $book->setTitle('aaaa')
            ->setTitleString('aaaa')
            ->setAuthorFirstName('Jim')
            ->setAuthorLastName('Smith')
            ->setYear('1980')
            ->setUser($user);
        if ($softDeleted) {
            $book->setDeletedAt(new \DateTime());
        }
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }
}