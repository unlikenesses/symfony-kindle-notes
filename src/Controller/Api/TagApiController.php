<?php

namespace App\Controller\Api;

use App\Entity\Tag;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TagApiController extends ApiController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/tags", name="apiTags")
     */
    public function getTags()
    {
        $tagRepo = $this->entityManager->getRepository(Tag::class);
        $tags = $tagRepo->getTagsForUser($this->getUser());

        return $this->createApiResponse(['tags' => $tags]);
    }
}