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

    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM AppBundle:Post p
                WHERE p.title LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
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
}
