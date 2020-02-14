<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function findNoteInBook(array $noteMetadata, Book $book): ?Note
    {
        $date = '';

        if (isset($noteMetadata['date'])) {
            $timestamp = strtotime($noteMetadata['date']);
            $date = date('Y-m-d H:i:s', $timestamp);
        }

        $query = $this->createQueryBuilder('n')
            ->andWhere('n.book = :book')
            ->andWhere('n.date = :date')
            ->setParameter('book', $book)
            ->setParameter('date', $date);

        if (isset($note['meta']['page'])) {
            $query->andWhere('n.page = :page')->setParameter('page', $noteMetadata['page']);
        }

        if (isset($note['meta']['location'])) {
            $query->andWhere('n.location = :location')->setParameter('location', $noteMetadata['location']);
        }

        return $query->getQuery()->getOneOrNullResult();
    }
}
