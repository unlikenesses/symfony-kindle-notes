<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use App\DataFixtures\AppFixtures;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTestCase extends WebTestCase
{
    use FixturesTrait;

    protected BookRepository $bookRepository;
    protected NoteRepository $noteRepository;
    protected UserRepository $userRepository;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookRepository = $entityManager
            ->getRepository(Book::class);
        $this->noteRepository = $entityManager
            ->getRepository(Note::class);
        $this->userRepository = $entityManager
            ->getRepository(User::class);

        $this->loadFixtures([
            AppFixtures::class,
        ]);
    }

    protected function loginUser()
    {
        $user = $this->getUser();
        $this->client->loginUser($user);
    }

    protected function getUser(): ?User
    {
        $users = $this->userRepository->findAll();

        return $users[0];
    }

    protected function getClientResponse()
    {
        return json_decode($this->client->getResponse()->getContent());
    }

    protected function getData()
    {
        $response = $this->getClientResponse();

        return $response->data;
    }
}