<?php

namespace App\Repository;

use App\Entity\ResesrvationDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method ResesrvationDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResesrvationDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResesrvationDetail[]    findAll()
 * @method ResesrvationDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResesrvationDetailRepository extends ServiceEntityRepository
{

    private $manager;
    public function __construct(ManagerRegistry $registry , EntityManagerInterface $manager)
    {
        parent::__construct($registry, ResesrvationDetail::class);
        $this->manager =$manager;
    }

    public function save($newReservationDetail)
    {
        $this->manager->persist($newReservationDetail);
        $this->manager->flush();
        return $newReservationDetail;
    }   
    public function findByLocal($local){
            return $this->manager->createQuery('SELECT date_debut ,date_fin FROM resesrvation_detail r
             WHERE r.local_id = :local ')
                ->setParameter('local',$local)
                ->getResult();
                

    }

    // /**
    //  * @return ResesrvationDetail[] Returns an array of ResesrvationDetail objects
    //  */
    
    public function findByExampleField($local)
    {
        $currentDate = strtotime(date('d M Y')); 
        return $this->createQueryBuilder('r')
            ->andWhere('r.local = :local')
            ->andWhere('r.dateDebut >= :currentDate')
            ->setParameter('local', $local)
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?ResesrvationDetail
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
