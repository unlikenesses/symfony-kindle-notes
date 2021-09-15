<?php

namespace App\Controller\Api;

use App\Entity\Note;
use App\Entity\User;
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
     * @Route("/api/deleted/notes", name="apiDeletedNotes", methods="GET")
     */
    public function getDeletedNotes(): JsonResponse
    {
        $notes = $this->findDeletedNotesForUser($this->getUser());
        $models = [];
        foreach ($notes as $note) {
            $models[] = $this->createNoteApiModel($note);
        }

        return $this->createApiResponse(['data' => $models]);
    }

    private function findDeletedNotesForUser(User $user): array
    {
        return $this->entityManager->getRepository(Note::class)
            ->findDeletedByUser($user);
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
