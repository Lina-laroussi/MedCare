<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 *
 * @method RendezVous|null find($id, $lockMode = null, $lockVersion = null)
 * @method RendezVous|null findOneBy(array $criteria, array $orderBy = null)
 * @method RendezVous[]    findAll()
 * @method RendezVous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    public function save(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RendezVous $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RendezVous[] Returns an array of RendezVous objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findRendezVousesBydate($date)
    {
        return $this->createQueryBuilder('r')
        ->Where('r.date = :val')
        ->setParameter('val', $date)
        ->getQuery()
        ->getResult()
        ;
   }
   public function findUpcomingRendezVouses($date)
   {
       return $this->createQueryBuilder('r')
       ->Where('r.date >= :val')
       ->setParameter('val', $date)
       ->getQuery()
       ->getResult()
       ;
  }
/*public function findByDate($cin)
    {

      $req=$this->createQueryBuilder('c');
      if($cin!=null)
      {
      $req->select('c.ref')
      ->join('c.utilisateur','u')
      ->addSelect('u.nom')//s.name et c.name : meme nom affiche l'un des deux---->ajouter l'alias :addSelect('c.name t')
      ->where('u.cin=:t')
      ->andWhere("c.agence='BiatTunis'")
      ->setParameter('t',$cin);
      }
      
      $res = $req->getQuery()
      ->getResult();
      dd($res);
      //return $req;
       
    }
*/
}