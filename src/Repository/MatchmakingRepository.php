<?php

namespace App\Repository;

use App\Entity\Matchmaking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matchmaking>
 *
 * @method Matchmaking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matchmaking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matchmaking[]    findAll()
 * @method Matchmaking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchmakingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matchmaking::class);
    }

    //    /**
    //     * @return Matchmaking[] Returns an array of Matchmaking objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Matchmaking
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
