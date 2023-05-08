<?php

namespace App\Repository;

use App\Entity\FicheMedicale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheMedicale>
 *
 * @method FicheMedicale|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheMedicale|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheMedicale[]    findAll()
 * @method FicheMedicale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheMedicaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheMedicale::class);
    }

    public function save(FicheMedicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FicheMedicale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTous($page,$nbre)
    {
        
        return $this->createQueryBuilder('p')
        ->orderBy('p.id', 'ASC')
        ->setFirstResult(($page - 1 ) * $nbre)
        ->setMaxResults($nbre)
        ->getQuery()
        ->getResult();
   }
   public function countFichMed(): int
   {
       return $this->createQueryBuilder('p')
           ->select('count(p.id)')
           ->getQuery()
           ->getSingleScalarResult()
           ;
   }

//    /**
//     * @return FicheMedicale[] Returns an array of FicheMedicale objects
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

//    public function findOneBySomeField($value): ?FicheMedicale
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
