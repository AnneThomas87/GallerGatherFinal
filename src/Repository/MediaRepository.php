<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    private $entityManagerInterface;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Media::class);
        $this->entityManagerInterface = $entityManager;
    }

    //    /**
    //     * @return Media[] Returns an array of Media objects
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

    //    public function findOneBySomeField($value): ?Media
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


  public function findImageOrga() 
    {
        $conn = $this->getEntityManager()->getConnection();
        

        $sql = 'SELECT m.*, l.* , u.* FROM media m JOIN lieu l ON m.lieu_id =  l.id JOIN user u ON l.organisateur_id = u.id
        ';

      
        $resultSet = $conn->executeQuery($sql);
        return $resultSet->fetchAllAssociative();
    }


  public function findImagePro() 
  {
      $conn = $this->getEntityManager()->getConnection();
      

      $sql = 'SELECT m.*, p.* , u.* FROM media m JOIN profil p ON m.profil_id =  p.id JOIN user u ON p.artiste_id = u.id
      ';

    
      $resultSet = $conn->executeQuery($sql);
      return $resultSet->fetchAllAssociative();
  }

}

  
