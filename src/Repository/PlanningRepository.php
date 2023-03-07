<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findTous($userId,$page,$nbre)
    {
        return $this->createQueryBuilder('p')
            ->join('p.medecin','m')
            ->Where('m.email = :val')
            ->setParameter('val',$userId)
            ->orderBy('p.date_debut', 'ASC')
            ->setFirstResult(($page - 1 ) * $nbre)
            ->setMaxResults($nbre)
            ->getQuery()
            ->getResult();
   }
    public function findByMedecin($userId)
    {
        return $this->createQueryBuilder('p')
            ->join('p.medecin','m')
            ->Where('m.id = :val')
            ->setParameter('val',$userId)
            ->getQuery()
            ->getResult();
    }
   public function countPlanning(): int
   {
       return $this->createQueryBuilder('p')
           ->select('count(p.id)')
           ->getQuery()
           ->getSingleScalarResult()
           ;
   }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}