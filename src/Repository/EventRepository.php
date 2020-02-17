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

    public function findEventByFilters(
        $beginDate,
        $endDate,
        $eventOwner,
        $userId,
        $user,
        $passedEvent,
        $subscribed,
        $notSubscribed,
        $var,
        $site,
        $page = 0,
        $limit = 100
    ) {

        $qb = $this->createQueryBuilder('e');
        $qb
            ->andWhere('e.site = :site')
            ->setParameter(':site', $site);
        if ($var != null) {
            $qb
                ->andWhere('e.label LIKE :var')
                ->orWhere('e.description LIKE :var')
                ->setParameter(':var', "%".$var."%");
        }
        if ($beginDate != null) {
            $qb
                ->andWhere('e.dateStart >= :beginDate')
                ->setParameter(':beginDate', $beginDate);
        }
        if ($endDate != null) {
            $qb
                ->andWhere('e.dateStart <= :endDate')
                ->setParameter(':endDate', $endDate);
        }
        if ($passedEvent == 'on') {
            $qb
                ->andWhere('e.dateStart <= :now')
                ->setParameter('now', new \DateTime());
        }
        if ($eventOwner == 'on') {
            $qb
                ->andWhere('e.creator = :userId')
                ->setParameter(':userId', $userId);

        }
//    if  ($subscribed == 'on' && $notSubscribed == 'on' ){
//
//    }
//
//
//
//
//
        if ($subscribed == 'on') {
            $qb
                ->addselect('i')
                ->innerJoin('e.inscriptions', 'i')
                ->andWhere('i.user = :user')
                ->setParameter(':user', $user);
                dump($user);

        }
        if ($notSubscribed == 'on') {
            $qb

                ->addselect('i')
                ->innerJoin('e.inscriptions', 'i')
                ->andWhere('i.user != :user')
                ->setParameter(':user', $user);
            dump($user);

        }


        $query = $qb->getQuery();


        dump($query->getSQL());
        $result = $query->getResult();

        return ($result);
    }


    public function findEventBySite($site, $page = 0, $limit = 10)
    {

        $entityManager = $this->getEntityManager();
        $dql           = <<<DQL
SELECT e
FROM APP\ENTITY\Event e
WHERE e.site = :site
DQL;


        $query = $entityManager
            ->createQuery($dql)
            ->setParameter(':site', $site)
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);
        $paginator = new Paginator($query, true);


//        return $query->getResult();
        return ($paginator);
    }




}
