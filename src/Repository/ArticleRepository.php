<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArticleRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return mixed
     */
    public function loadAll($limit = 6, $offset = 0)
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->orderBy('a.createdAt', 'DESC');

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @return int The number of articles stored in MySQL
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countQuery()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        $queryBuilder->select('COUNT(a.id)');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }
    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
