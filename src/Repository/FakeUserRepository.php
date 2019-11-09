<?php

namespace App\Repository;

use App\Entity\FakeUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FakeUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method FakeUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method FakeUser[]    findAll()
 * @method FakeUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FakeUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FakeUser::class);
    }

    // /**
    //  * @return FakeUser[] Returns an array of FakeUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FakeUser
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
