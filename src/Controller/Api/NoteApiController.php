<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use App\Exception\PermaDeleteActiveBookOrNoteException;
use App\Exception\RestoreActiveBookOrNoteException;
use App\Exception\WrongOwnerException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class NoteApiController extends ApiController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        return $this->em->getRepository(Note::class)
            ->findDeletedByUser($user);
    }

    /**
     * @Route("/api/notes/{note}", name="apiDeleteNote", methods="DELETE")
     */
    public function deleteNote(Note $note): JsonResponse
    {
        if ($note->getUser()->getId() !== $this->getUser()->getId()) {
            throw new WrongOwnerException();
        }
        $note->setDeletedAt(new \DateTime());
        $this->em->flush();

        return $this->createApiResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/notes/permaDelete", name="apiPermaDeleteNotes", methods="PUT")
     */
    public function permaDeleteNotes(Request $request): JsonResponse
    {
        $noteIds = json_decode($request->getContent());
        foreach ($noteIds as $noteId) {
            /** @var Note $note */
            $note = $this->em->getRepository(Note::class)->find($noteId);
            if ($note->getUser()->getId() !== $this->getUser()->getId()) {
                throw new WrongOwnerException();
            }
            if (is_null($note->getDeletedAt())) {
                throw new PermaDeleteActiveBookOrNoteException();
            }
            $this->em->remove($note);
            $this->em->flush();
        }

        return $this->createApiResponse(['message' => 'success']);
    }

    /**
     * @Route("/api/notes/restore", name="apiRestoreNotes", methods="PUT")
     */
    public function restoreNotes(Request $request): JsonResponse
    {
        $noteIds = json_decode($request->getContent());
        foreach ($noteIds as $noteId) {
            /** @var Note $note */
            $note = $this->em->getRepository(Note::class)->find($noteId);
            if ($note->getUser()->getId() !== $this->getUser()->getId()) {
                throw new WrongOwnerException();
            }
            if (is_null($note->getDeletedAt())) {
                throw new RestoreActiveBookOrNoteException();
            }
            $note->setDeletedAt(null);
            $this->em->flush();
        }

        return $this->createApiResponse(['message' => 'success']);
    }
}
