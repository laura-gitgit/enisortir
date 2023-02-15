<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findAllBySite($nomSite)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.site', 'site')
            ->addSelect('site')
            ->addOrderBy('s.dateHeureDebut')
            ->where('site.nom = :nomSite')
            ->setParameter('nomSite', $nomSite)
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }

    public function findAllByNameAndSite($nomSite, $nomSortie)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.site', 'site')
            ->addSelect('site')
            ->addOrderBy('s.dateHeureDebut')
            ->where('s.nom LIKE :nomSortie')
            ->andWhere('site.nom = :nomSite')
            ->setParameter('nomSite', $nomSite)
            ->setParameter('nomSortie', '%'.$nomSortie.'%')
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }

    public function findAllBySiteNameAndDate($nomSite, $dateBefore, $dateAfter)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.site', 'site')
            ->addSelect('site')
            ->addOrderBy('s.dateHeureDebut')
            ->andWhere('site.nom = :nomSite')
            ->andWhere('s.dateHeureDebut BETWEEN :dateBefore AND :dateAfter')
            ->setParameter('nomSite', $nomSite)
            ->setParameter('dateBefore', $dateBefore)
            ->setParameter('dateAfter', $dateAfter)
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }
    public function findAllByAllParameters($nomSite, $nomSortie, $dateBefore, $dateAfter)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.site', 'site')
            ->addSelect('site')
            ->addOrderBy('s.dateHeureDebut')
            ->where('s.nom LIKE :nomSortie')
            ->andWhere('site.nom = :nomSite')
            ->andWhere('s.dateHeureDebut BETWEEN :dateBefore AND :dateAfter')
            ->setParameter('nomSortie', '%'.$nomSortie.'%')
            ->setParameter('nomSite', $nomSite)
            ->setParameter('dateBefore', $dateBefore)
            ->setParameter('dateAfter', $dateAfter)
            ->getQuery()
            ->setMaxResults(10)
            ->getResult();
    }

//    public function findAllByIsOrganisateur (boolean $isOrga){
//
//    }


//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
   // public function supprimerSortie($id)
  //  {
    //    $query = $this->createQueryBuilder('s')
    //        ->delete('Sortie')
//            ->where('s.id= id')
//            ->setParameter('id',$id)
//            ->getQuery();
//
//
//    }

}
