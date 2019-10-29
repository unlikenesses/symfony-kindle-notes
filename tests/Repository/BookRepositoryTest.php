<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->bookRepository = $this->entityManager
            ->getRepository(Book::class);
    }

    public function testFindOneByTitleString()
    {
        $book = $this->bookRepository
            ->findOneByTitleString('Test title');
        $this->assertEquals('Fred', $book->getAuthorFirstName());
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}