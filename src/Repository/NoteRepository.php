<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Note;
use App\Entity\User;
use App\ValueObject\NoteMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function getUndeletedNotesForBook(Book $book)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.book = :book')
            ->andWhere('n.deletedAt IS NULL')
            ->setParameter('book', $book)
            ->getQuery()
            ->getResult();
    }

    public function findDeletedByUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->andWhere('b.deletedAt IS NOT NULL')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }

    public function findNoteInBook(NoteMetadata $noteMetadata, Book $book): ?Note
    {
        $date = '';

        if ($noteMetadata->getDate()) {
            $timestamp = strtotime($noteMetadata->getDate());
            $date = date('Y-m-d H:i:s', $timestamp);
        }

        $query = $this->createQueryBuilder('n')
            ->andWhere('n.book = :book')
            ->andWhere('n.date = :date')
            ->setParameter('book', $book)
            ->setParameter('date', $date);

        if ($noteMetadata->getPage()) {
            $query->andWhere('n.page = :page')->setParameter('page', $noteMetadata->getPage());
        }

        if ($noteMetadata->getLocation()) {
            $query->andWhere('n.location = :location')->setParameter('location', $noteMetadata->getLocation());
        }

        return $query->getQuery()->getOneOrNullResult();
    }

    public function countNotes(): int
    {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->andWhere('n.deletedAt IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
