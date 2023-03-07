<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function save(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }


      
    }
    


public function TotalFactures(): int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalRevenus(): int
   { $qb = $this->createQueryBuilder('c')
          ->select('SUM(c.montant)');

       return (int) $qb->getQuery()->getSingleScalarResult(); }
 

  /*  public function findtous($nbre , $page) 
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC')
            ->setFirstResult(($page - 1 )* $nbre)
            ->setMaxResults($nbre)
            ->getQuery()
            ->getResult();
            
    }



public function countbydate($date): int
{
    return $this->createQueryBuilder('r')
        ->select('count(r.id)')
        ->Where('r.date = :val')
        ->setParameter('val', $date)
        ->getQuery()
        ->getSingleScalarResult()
        ;
}
*/
public function countByDate(){
$query = $this->createQueryBuilder('a')
->select('SUBSTRING(a.date, 1, 10) as datefactures, COUNT(a) as count')
->groupBy('datefactures')
;
return $query->getQuery()->getResult();
}

public function countfactures(): int
{
    return $this->createQueryBuilder('r')
        ->select('count(r.id)')
        ->getQuery()
        ->getSingleScalarResult();
}

public function countfacturesperpharmacie()
{
    return $this->createQueryBuilder('f')
        ->select('COUNT(f.id) as factureCount', 'ph.nom as pharmacieName')


        ->join('f.pharmacie', 'ph')
        ->groupBy('ph.id')
        ->getQuery()
        ->getResult();
}


//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
