<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByCategoryAndUser(string $category, User $user): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.category', 'c')
            ->andWhere('c.name = :category')
            ->andWhere('b.user = :user')
            ->setParameter('category', $category)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findOneByTitleString(string $titleString): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.titleString = :val')
            ->setParameter('val', $titleString)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
