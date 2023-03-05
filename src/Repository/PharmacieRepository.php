<?php

namespace App\Repository;

use App\Entity\Pharmacie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pharmacie>
 *
 * @method Pharmacie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pharmacie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pharmacie[]    findAll()
 * @method Pharmacie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PharmacieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pharmacie::class);
    }

    public function save(Pharmacie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pharmacie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByString($nom){
        return $this->createQueryBuilder('pharmacie')
            ->where('pharmacie.nom like :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }


    public function __toString(){
        //return $this->id;
        return (string)$this->id;
        
    }
    public function findName(string $term): array
    {

        $qb = $this->createQueryBuilder('p');

        return $qb->where($qb->expr()->like('p.nom', ':term'))
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }
    public function findPharmacieBySearchTerm($searchTerm): array
    {
       return $this->createQueryBuilder('u')
           ->where('u.nom LIKE :searchTerm OR
            u.email Like :searchTerm OR
            u.matricule LIKE :searchTerm OR 
            u.adresse Like :searchTerm OR
            u.gouvernorat LIKE :searchTerm OR
            u.etat LIKE :searchTerm' )
           ->setParameter('searchTerm', $searchTerm)
           ->getQuery()
           ->getResult()
       ;
    }

    public function findOneByGouvernorat($gouvernorat): ?Facture
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.gouvernorat = :val')
            ->setParameter('val', $gouvernorat)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
 

   /*public function findBeginWith($value, $userId)
    {
        if($userId == null) {
            return $this->createQueryBuilder('a')
                ->andWhere('a.title LIKE :val or a.description LIKE :val')
                ->setParameter('val', '%'.$value.'%')
                ->orderBy('a.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :id')
            ->setParameter('id', $userId)
            ->andWhere('a.title LIKE :val or a.description LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
*/




















/*public function findNom($searchquery)
{
            return $this->createQueryBuilder('p')
                ->andWhere('p.nom LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchquery.'%')
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
          ;
        }

/*



//    /**
//     * @return Pharmacie[] Returns an array of Pharmacie objects
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

//    public function findOneBySomeField($value): ?Pharmacie
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

