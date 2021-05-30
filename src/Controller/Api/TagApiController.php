<?php

namespace App\Controller\Api;

use App\Entity\Note;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TagApiController extends ApiController
{
    private $entityManager;

    private const MAX_TAGS = 25;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/tags/{tag}/notes", name="apiTagNotes")
     */
    public function getNotesForTag(Tag $tag): JsonResponse
    {
        $models = [];
        $numHighlights = 0;
        $numNotes = 0;
        foreach ($this->getNotes($tag) as $note) {
            $models[] = $this->createNoteApiModel($note, true);
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

    private function getNotes(Tag $tag)
    {
        return $tag->getNotes();
    }

    /**
     * @Route("/api/notes/{noteId}/tags", name="apiUpdateNoteTags", methods="PUT")
     */
    public function updateNoteTags($noteId, Request $request): JsonResponse
    {
        /** @var Note $note */
        $note = $this->entityManager->getRepository(Note::class)->find($noteId);
        $tags = json_decode($request->getContent());
        if ($this->addingTag($note, $tags) && $this->overTagLimit($tags)) {
            $message = 'too many tags';
            return $this->createApiResponse([
                'message' => $message,
                'error' => self::API_ERROR_CODES[$message],
            ], 500);
        }
        $tagIds = $this->syncNoteTags($note, $tags);

        return $this->createApiResponse(['message' => 'success']);
    }

    private function addingTag(Note $note, array $tags): bool
    {
        return count($tags) > count($note->getTags());
    }

    private function overTagLimit(array $tags): bool
    {
        /** @var User $user */
        $user = $this->getUser();
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        $tagCount = $tagRepo->getUserTagCount($user);
        $existingUserTags = $tagRepo->getTagsForUser($user)->fetchAllAssociative();
        $existingUserTags = array_map(function ($row) {
            return $row['name'];
        }, $existingUserTags);

        return $tagCount[0] >= self::MAX_TAGS && array_diff($tags, $existingUserTags);
    }

    private function syncNoteTags(Note $note, array $tagNames): array
    {
        $tags = $this->getTagEntities($tagNames);
        $note->syncTags($tags);
        $this->entityManager->persist($note);
        $this->entityManager->flush();

        return $tags;
    }

    private function getTagEntities(array $tagNames): array
    {
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tags[] = $this->getTagEntity($tagName);
        }

        return $tags;
    }

    private function getTagEntity(string $tagName): Tag
    {
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        /** @var Tag $tag */
        $tag = $tagRepo->findOneBy(['name' => $tagName]);
        if ($tag) {
            return $tag;
        }
        $tag = new Tag();
        $tag->setName($tagName);
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return $tag;
    }

    /**
     * @Route("/api/tags", name="apiTags")
     */
    public function getTags(): Response
    {
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        $tags = $tagRepo->getTagsForUser($this->getUser());

        return $this->createApiResponse(['tags' => $tags]);
    }
}