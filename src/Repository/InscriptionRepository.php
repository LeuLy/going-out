<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }



//    public function findSubscribedByEvent($event)
//    {
//        $entityManager = $this->getEntityManager();
//        $dql           = <<<DQL
//SELECT i
//FROM APP\ENTITY\Inscription i
//WHERE i.event = :event
//DQL;
//        $query     = $entityManager
//            ->createQuery($dql)
//            ->setParameter(':event', $event);
//
//        dump($query->getSQL());
//
//
//        return $query->getResult();
//    }

    public function findSubscribedByEvent($event)
    {
        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL

SELECT i 
FROM APP\ENTITY\Inscription i
WHERE i.event = :event
DQL;
        $query     = $entityManager
            ->createQuery($dql)
            ->setParameter(':event', $event);

        dump($query->getSQL());


        return $query->getResult();
    }


    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
