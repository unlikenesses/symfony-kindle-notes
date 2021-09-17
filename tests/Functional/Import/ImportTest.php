<?php

namespace App\Tests\Functional\Import;

use App\DataFixtures\UserFixtures;
use App\Service\FileHandler;
use App\Service\FileReader;
use App\Service\NoteSaver;
use App\Service\Parser\Paperwhite\PaperwhiteLineReader;
use App\Service\Parser\Paperwhite\PaperwhiteTitleStringParser;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\Traits\HasAPIInteraction;
use App\ValueObject\BookList;
use App\ValueObject\FileToImport;

class ImportTest extends FunctionalTestCase
{
    use HasAPIInteraction;

    const PATH_TO_CLIPPINGS_FILE = __DIR__ . '/../../../src/DataFixtures/';
    const MINIMAL_CLIPPINGS_FILE = 'MinimalClippings.txt';
    const MINIMAL_CLIPPINGS_BOOK_COUNT = 2;
    const MINIMAL_CLIPPINGS_NOTE_COUNT = 7;

    private FileHandler $fileHandler;
    private NoteSaver $noteSaver;

    protected function setUp(): void
    {
        parent::setUp();
        $fileReader = new FileReader();
        $lineReader = new PaperwhiteLineReader();
        $titleStringParser = new PaperwhiteTitleStringParser();
        $this->fileHandler = new FileHandler($fileReader, $lineReader, $titleStringParser);
        $this->noteSaver = new NoteSaver($this->entityManager, $this->bookRepository, $this->noteRepository);
        $this->loadFixtures([
            UserFixtures::class,
        ]);
    }

    public function testImportingCreatesBooksAndNotes()
    {
        // Start with zero books and notes
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(0, $bookCount);
        $this->assertEquals(0, $noteCount);
        // Import file with x books and y notes
        $fileToImport = $this->getFileToImport(self::MINIMAL_CLIPPINGS_FILE);
        $books = $this->getBookList($fileToImport);
        $this->assertEquals(BookList::class, get_class($books));
        $this->noteSaver->storeNotes($books, $fileToImport->getUserId());
        // Confirm they are in the database
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_BOOK_COUNT, $bookCount);
        $this->assertEquals(self::MINIMAL_CLIPPINGS_NOTE_COUNT, $noteCount);
    }

    public function testImportingFileWithNoChangesDoesNotReimportNotes()
    {
        // Start with zero books and notes
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(0, $bookCount);
        $this->assertEquals(0, $noteCount);
        // Import file with x books and y notes
        $books = $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        $this->assertEquals(BookList::class, get_class($books));
        // Confirm they are in the database
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_BOOK_COUNT, $bookCount);
        $this->assertEquals(self::MINIMAL_CLIPPINGS_NOTE_COUNT, $noteCount);
        // Reimport
        $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        // Confirm counts are the same
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_BOOK_COUNT, $bookCount);
        $this->assertEquals(self::MINIMAL_CLIPPINGS_NOTE_COUNT, $noteCount);
    }

    public function testDeletedBooksAreNotReImported()
    {
        // Start with zero books and notes
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(0, $bookCount);
        $this->assertEquals(0, $noteCount);
        // Import file with x books and y notes
        $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        // Delete first book via API (need to login for that)
        $this->loginUser();
        $this->deleteBook(1);
        // Confirm book count is 1 less
        $bookCount = $this->bookRepository->countBooks();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_BOOK_COUNT - 1, $bookCount);
        // Import same file
        $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        // Confirm book count has not increased (with 2 methods)
        $bookCount = $this->bookRepository->countBooks();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_BOOK_COUNT - 1, $bookCount);
        $books = $this->getBooks();
        $this->assertCount(self::MINIMAL_CLIPPINGS_BOOK_COUNT - 1, $books);
    }

    public function testDeletedNotesAreNotReImported()
    {
        // Start with zero books and notes
        $bookCount = $this->bookRepository->countBooks();
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(0, $bookCount);
        $this->assertEquals(0, $noteCount);
        // Import file with x books and y notes
        $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        // Delete first note via API (need to login for that)
        $this->loginUser();
        $this->deleteNote(1);
        // Confirm note count is 1 less
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_NOTE_COUNT - 1, $noteCount);
        // Import same file
        $this->doImport(self::MINIMAL_CLIPPINGS_FILE);
        // Confirm note count has not increased
        $noteCount = $this->noteRepository->countNotes();
        $this->assertEquals(self::MINIMAL_CLIPPINGS_NOTE_COUNT - 1, $noteCount);
    }

    private function doImport(string $filename): BookList
    {
        $fileToImport = $this->getFileToImport($filename);
        $books = $this->getBookList($fileToImport);
        $this->noteSaver->storeNotes($books, $fileToImport->getUserId());

        return $books;
    }

    private function getFileToImport(string $filename): FileToImport
    {
        $path = self::PATH_TO_CLIPPINGS_FILE . $filename;

        return new FileToImport($path, $this->getUser());
    }

    private function getBookList(FileToImport $fileToImport): BookList
    {
        return $this->fileHandler->processFile($fileToImport);
    }
}