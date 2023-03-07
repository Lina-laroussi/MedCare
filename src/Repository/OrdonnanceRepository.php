<?php

namespace App\Repository;

use App\Entity\Ordonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Ordonnance>
 *
 * @method Ordonnance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordonnance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordonnance[]    findAll()
 * @method Ordonnance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }

    public function save(Ordonnance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ordonnance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //------------------------ pagination ------------------------------------------
    public function findTous($page,$nbre)
    {
        
        return $this->createQueryBuilder('p')
        ->orderBy('p.id', 'ASC')
        ->setFirstResult(($page - 1 ) * $nbre)
        ->setMaxResults($nbre)
        ->getQuery()
        ->getResult();
   }
   public function countOrdonnance(): int
   {
       return $this->createQueryBuilder('p')
           ->select('count(p.id)')
           ->getQuery()
           ->getSingleScalarResult()
           ;
   }
//----------------stat: chart => nbre de med per day -----------------------------------------------------

    public function getTotalMedicament(): int
        {
            $qb = $this->createQueryBuilder('c')
                ->select('COUNT(c.medicaments)');

            return (int) $qb->getQuery()->getSingleScalarResult();
        }



   /*public function findMedicamentsAddedPerDay(): array
    {
        $qb = $this->createQueryBuilder('o')
            ->select('DATE(o.createdAt) as day, COUNT(o.id) as count')
            ->groupBy('day')
            ->orderBy('day', 'ASC');
            
        return $qb->getQuery()->getArrayResult();
    }

    
    public function countMedicationsByDay(int $month): array
    {
        $query = $this->createQueryBuilder('o')
            ->select('DATE(o.date_de_creation) AS day, COUNT(o.id) AS count')
            ->where('MONTH(o.date_de_creation) = :month')
            ->setParameter('month', $month)
            ->groupBy('day')
            ->getQuery();

        return $query->getResult();
    }


//    /**
//     * @return Ordonnance[] Returns an array of Ordonnance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ordonnance
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
