<?php

namespace App\Controller\Api;

use App\Entity\Note;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/api/notes/{noteId}/tags", name="apiUpdateNoteTags", methods="PUT")
     */
    public function updateNoteTags($noteId, Request $request): JsonResponse
    {
        $tags = json_decode($request->getContent());
        $tagIds = $this->syncNoteTags($noteId, $tags);

        return $this->createApiResponse(['message' => 'success']);
    }

    private function syncNoteTags(int $noteId, array $tagNames): array
    {
        /** @var Note $note */
        $note = $this->entityManager->getRepository(Note::class)->find($noteId);
        $tags = $this->getTags($tagNames);
        $note->syncTags($tags);
        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return $tags;
    }

    private function getTags(array $tagNames): array
    {
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[] = $this->getTag($tagName);
        }

        return $tags;
    }

    private function getTag(string $tagName): Tag
    {
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        /** @var Tag $tag */
        if ($tag = $tagRepo->findOneBy(['name' => $tagName])) {
            return $tag;
        }
        $tag = new Tag();
        $tag->setName($tagName);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $tag;
    }
}
