<?php

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Entity\Book;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookRepository = $this->entityManager
            ->getRepository(Book::class);

        $this->loadFixtures([
            AppFixtures::class,
        ]);
    }

    public function testFindOneByTitleString()
    {
        $book = $this->bookRepository
            ->findOneByTitleString('Test title');
        $this->assertNotNull($book);
        $this->assertEquals('Fred', $book->getAuthorFirstName());
    }
}