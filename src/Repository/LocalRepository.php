<?php

namespace App\Repository;

use App\Entity\Local;
use App\Entity\ResesrvationDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Local|null find($id, $lockMode = null, $lockVersion = null)
 * @method Local|null findOneBy(array $criteria, array $orderBy = null)
 * @method Local[]    findAll()
 * @method Local[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalRepository extends ServiceEntityRepository
{
    private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Local::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Local[] Returns an array of Local objects
    //  */

    public function findByExampleField($nbrEnfant , $nbrAdulte , $type)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.maxEnfant >= :nbrEnfant')
            ->andWhere('r.maxAdulte >= :nbrAdulte')
            ->andWhere('r.type = :type')
            ->setParameter('nbrEnfant', $nbrEnfant)
            ->setParameter('nbrAdulte', $nbrAdulte)
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult()
        ;
    }
        
    public function findLocalLibre($nbrEnfant , $nbrAdulte , $type, $dateFin, $dateDebut)
    {

        $conn = $this->manager->getConnection();
        $sql = 'SELECT * FROM local l  WHERE l.type_id = :type and l.max_enfant >= :nbrEnfant and l.max_adulte >= :nbrAdulte and l.id not in (select local_id from `resesrvation_detail` where date_fin >= :dateDebut and date_debut <= :dateFin);';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("type", $type);
        $stmt->bindValue("nbrEnfant", $nbrEnfant);
        $stmt->bindValue("nbrAdulte", $nbrAdulte);
        $stmt->bindValue("dateDebut", $dateDebut);
        $stmt->bindValue("dateFin", $dateFin);
        $result = $stmt->executeQuery(); 
        return $result->fetchAllAssociative();
    }
    /*
    public function findOneBySomeField($value): ?Local
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function saveLocal($newLocal)
    {
        $this->manager->persist($newLocal);
        $this->manager->flush();
    }
     public function updateLocal(Local $local): Local
    {
           $this->manager->persist($local);
           $this->manager->flush();
           return $local;
    } 
    public function removeLocal(Local $local)
    {
            $this->manager->remove($local);
            $this->manager->flush();
    }
}
