<?php

namespace App\Controller\Api;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteApiController extends ApiController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/notes/{noteId}", name="apiDeleteNote", methods="DELETE")
     */
    public function deleteNote($noteId): JsonResponse
    {
        $note = $this->entityManager->getRepository(Note::class)->find($noteId);
        $note->setDeletedAt(new \DateTime());
        $this->entityManager->flush();

        return $this->createApiResponse(['message' => 'success']);
    }
}
