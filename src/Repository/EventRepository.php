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


//        return $query->getResult();
        return ($paginator);
    }


    public function findEventByDate($endDate, $beginDate, $var, $site, $page = 0, $limit = 100)
    {


        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL
SELECT e
FROM APP\ENTITY\Event e
WHERE e.site          = :site
AND e.dateStart BETWEEN :beginDate AND :endDate
DQL;

        $query = $entityManager
            ->createQuery($dql)
            ->setParameter(':endDate', $endDate)
            ->setParameter(':beginDate', $beginDate)
            ->setParameter(':site', $site)
            ->setParameter(':var', "%".$var."%")
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query, true);

        return $paginator;


    }


    public function findEventByDescription($var, $site, $page = 0, $limit = 100)
    {
        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL
SELECT e
FROM APP\ENTITY\Event e
WHERE e.site            = :site
AND (e.label            LIKE :var
OR e.description        LIKE :var)
DQL;

        $query = $entityManager
            ->createQuery($dql)
            ->setParameter(':site', $site)
            ->setParameter(':var', "%".$var."%")
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query, true);

        return $paginator;

    }



   



}
