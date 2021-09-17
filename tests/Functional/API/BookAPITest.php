<?php

namespace App\Tests\Functional\API;

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
        $this->assertSame('success', $response->message);
        // Check it is not returned by the API
        $books = $this->getBooks();
        $this->assertCount(1, $books);
        // Check the book still exists in the database
        $book = $this->bookRepository->find($bookToDelete);
        $this->assertNotNull($book);
        // Check its softDeleted column is set
        $this->assertNotNull($book->getDeletedAt());
    }
}