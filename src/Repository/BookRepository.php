<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

     /**
      * @return Book[] Returns an array of Book with Author objects
      */

    public function findOneToManyJoinedToAuthors()
    {
        return $this
            ->createQueryBuilder('b')
            ->leftJoin('b.authors', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $id
     *
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function findOneToManyJoinedToAuthorsBy(int $id)
    {
        return $this
            ->createQueryBuilder('b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('b.authors', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
