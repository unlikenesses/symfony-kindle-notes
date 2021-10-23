<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\User;
use App\Exception\PermaDeleteActiveBookOrNoteException;
use App\Exception\RestoreActiveBookOrNoteException;
use App\Exception\WrongOwnerException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookApiController extends ApiController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/books/category/{category}", name="apiBooks", methods="GET")
     */
    public function getBooks(?string $category): JsonResponse
    {
        if ($category === 'null') {
            $books = $this->findAllBooksForUser($this->getUser());
        } else {
            $books = $this->findAllBooksByCategoryForUser($category, $this->getUser());
        }
        $models = [];
        foreach ($books as $book) {
            $models[] = $this->createBookApiModel($book);
        }

        return $this->createApiResponse(['data' => $models]);
    }

    /**
     * @Route("/api/books/book/{book}", name="apiSingleBook", methods="GET")
     */
    public function getBook(Book $book): JsonResponse
    {
        $models = [
            $this->createBookApiModel($book),
        ];

        return $this->createApiResponse(['data' => $models]);
    }

    /**
     * @Route("/api/deleted/books", name="apiDeletedBooks", methods="GET")
     */
    public function getDeletedBooks(): JsonResponse
    {
        $books = $this->findDeletedBooksForUser($this->getUser());
        $models = [];
        foreach ($books as $book) {
            $models[] = $this->createBookApiModel($book);
        }

        return $this->createApiResponse(['data' => $models]);
    }

    private function findAllBooksForUser(User $user): array
    {
        return $this->em->getRepository(Book::class)
            ->findByUser($user);
    }

    private function findDeletedBooksForUser(User $user): array
    {
        return $this->em->getRepository(Book::class)
            ->findDeletedByUser($user);
    }

    private function findAllBooksByCategoryForUser(string $category, User $user): array
    {
        return $this->em->getRepository(Book::class)
            ->findByCategoryAndUser($category, $user);
    }

    /**
     * @Route("/api/books/{book}", name="apiDeleteBook", methods="DELETE")
     */
    public function deleteBook(Book $book): JsonResponse
    {
        if ($book->getUser()->getId() !== $this->getUser()->getId()) {
            throw new WrongOwnerException();
        }
        $book->setDeletedAt(new \DateTime());
        $this->em->flush();

        return $this->createApiResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/books/permaDelete", name="apiPermaDeleteBooks", methods="PUT")
     */
    public function permaDeleteBooks(Request $request): JsonResponse
    {
        $bookIds = json_decode($request->getContent());
        foreach ($bookIds as $bookId) {
            /** @var Book $book */
            $book = $this->em->getRepository(Book::class)->find($bookId);
            if ($book->getUser()->getId() !== $this->getUser()->getId()) {
                throw new WrongOwnerException();
            }
            if (is_null($book->getDeletedAt())) {
                throw new PermaDeleteActiveBookOrNoteException();
            }
            $this->em->remove($book);
            $this->em->flush();
        }

        return $this->createApiResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/books/restore", name="apiRestoreBooks", methods="PUT")
     */
    public function restoreBooks(Request $request): JsonResponse
    {
        $bookIds = json_decode($request->getContent());
        foreach ($bookIds as $bookId) {
            /** @var Book $book */
            $book = $this->em->getRepository(Book::class)->find($bookId);
            if ($book->getUser()->getId() !== $this->getUser()->getId()) {
                throw new WrongOwnerException();
            }
            if (is_null($book->getDeletedAt())) {
                throw new RestoreActiveBookOrNoteException();
            }
            $book->setDeletedAt(null);
            $this->em->flush();
        }

        return $this->createApiResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/books/{book}/notes", name="apiNotes")
     */
    public function getNotesForBook(Book $book): JsonResponse
    {
        $models = [];
        $numHighlights = 0;
        $numNotes = 0;
        foreach ($this->getNotes($book) as $note) {
            $models[] = $this->createNoteApiModel($note);
            if ($note->getType() === 1) {
                $numHighlights++;
            } elseif ($note->getType() === 2) {
                $numNotes++;
            }
        }

        return $this->createApiResponse([
            'data' => $models,
            'numHighlights' => $numHighlights,
            'numNotes' => $numNotes,
        ]);
    }

    private function getNotes(Book $book)
    {
        $noteRepo = $this->em->getRepository(Note::class);

        return $noteRepo->getUndeletedNotesForBook($book);
    }

    /**
     * @Route("/api/books/{book}/title", name="apiBookTitle", methods="PUT")
     */
    public function updateBookTitle(Book $book, Request $request): JsonResponse
    {
        // TODO: Check logged-in user owns this book
        $title = json_decode($request->getContent());
        $book->setTitle($title);
        $this->em->flush();

        return $this->createApiResponse([
            'message' => 'success',
        ]);
    }
}
