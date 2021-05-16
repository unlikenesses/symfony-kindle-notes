<?php

namespace App\Controller\Api;

use App\Entity\Note;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TagApiController extends ApiController
{
    private $entityManager;

    private const MAX_TAGS = 5;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/notes/{noteId}/tags", name="apiUpdateNoteTags", methods="PUT")
     */
    public function updateNoteTags($noteId, Request $request): JsonResponse
    {
        /** @var Note $note */
        $note = $this->entityManager->getRepository(Note::class)->find($noteId);
        $tags = json_decode($request->getContent());
        if ($this->addingTag($note, $tags) && $this->overTagLimit()) {
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

    private function overTagLimit(): bool
    {
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        $tagCount = $tagRepo->getUserTagCount($this->getUser());

        return $tagCount[0] >= self::MAX_TAGS;
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
        if ($tag = $tagRepo->findOneBy(['name' => $tagName])) {
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