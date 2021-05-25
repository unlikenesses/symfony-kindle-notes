<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/api/books/{category}", name="apiBooks")
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

    private function findAllBooksForUser(User $user): array
    {
        return $this->em->getRepository(Book::class)
            ->findByUser($user);
    }

    private function findAllBooksByCategoryForUser(string $category, User $user): array
    {
        return $this->em->getRepository(Book::class)
            ->findByCategoryAndUser($category, $user);
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
}
