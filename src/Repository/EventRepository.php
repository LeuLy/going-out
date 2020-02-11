<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findByLabel($value)
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function findEventBySite($site, $page = 0, $limit = 10)
    {

        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL
SELECT e
FROM APP\ENTITY\Event e
WHERE e.site = :site
DQL;


        $query     = $entityManager
            ->createQuery($dql)
            ->setParameter(':site', $site)
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);
        $paginator = new Paginator($query, true);

        return $paginator;
    }


    public function findEventByDescription($var, $page = 0, $limit = 100)
    {
        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL
 SELECT e
FROM APP\ENTITY\Event e
WHERE e.label       LIKE :var
or e.description    LIKE :var
DQL;

        $query     = $entityManager
            ->createQuery($dql)
            ->setParameter(':var', "%".$var."%")
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);
        $paginator = new Paginator($query, true);

        return $paginator;

    }

//
//    public function findEventBySite($site, $page = 0, $limit = 10)
//    {
//
//        $entityManager = $this->getEntityManager();
//        $dql           = <<<DQL
//SELECT s
//FROM APP\ENTITY\Site s
//JOIN s.events e
//WHERE s.label = :label
//DQL;
//
////SELECT i
////FROM APP\ENTITY\Site i
////WHERE i.label = :label
////DQL;
//
//        $query     = $entityManager
//            ->createQuery($dql)
//            ->setParameter(':label', $site)
//            ->setFirstResult($page * $limit)
//            ->setMaxResults($limit);
//        $paginator = new Paginator($query, true);
//
//        return $paginator;
//    }


    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
