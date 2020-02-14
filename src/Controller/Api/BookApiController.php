<?php

namespace App\Controller\Api;

use App\Entity\Book;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookApiController extends ApiController
{
    /**
     * @Route("/api/books", name="apiBooks")
     */
    public function getBooks(): JsonResponse
    {
        $models = $this->findAllBooksByUser($this->getUser());

        return $this->createApiResponse(['data' => $models]);
    }

    /**
     * @Route("/api/books/{book}/notes", name="apiNotes")
     */
    public function getNotesForBook(Book $book): JsonResponse
    {
        $models = [];
        $numHighlights = 0;
        $numNotes = 0;
        foreach ($book->getNotes() as $note) {
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
}
