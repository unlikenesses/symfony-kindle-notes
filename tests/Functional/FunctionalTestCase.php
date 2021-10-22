<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
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
    protected EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookRepository = $this->entityManager
            ->getRepository(Book::class);
        $this->noteRepository = $this->entityManager
            ->getRepository(Note::class);
        $this->userRepository = $this->entityManager
            ->getRepository(User::class);
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

    protected function createUser(string $email = 'another_user@example.com', string $password = 'pwdpwd'): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $this->entityManager->persist($user);

        return $user;
    }

    protected function getClientResponse()
    {
        return json_decode($this->client->getResponse()->getContent());
    }

    protected function getData()
    {
        $response = $this->getClientResponse();

        return $response->data ?? null;
    }
}