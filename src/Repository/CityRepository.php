<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findCityByVar($var)
    {

        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
SELECT c
FROM APP\ENTITY\City c
WHERE c.name LIKE :var
DQL;


        $query = $entityManager
                ->createQuery($dql)
                ->setParameter(':var', "%".$var."%");

        return $query->getResult();

    }

    public function findBySearch($search)
    {
        $regex = '%'.$search.'%';
        $qb = $this->createQueryBuilder('c');
        $qb
                ->andWhere('c.name LIKE :search')
                ->setParameter(':search', $regex);

        $query = $qb->getQuery();
        $result = $query->getResult();

        return ($result);
    }

    // /**
    //  * @return City[] Returns an array of City objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
