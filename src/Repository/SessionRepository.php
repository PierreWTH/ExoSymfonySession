<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function save(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Afficher les stagiaires non inscrits
    public function findNonInscrits($sessionId){

    $em = $this->getEntityManager();
    $sub = $em->CreateQueryBuilder();

    $qb = $sub;
    
    // Selectionner tous les stagiaires d'une session dont l'id est passé en paramètre
    $qb-> select('s')
        ->from('App\Entity\Stagiaire', 's')
        ->leftJoin('s.sessions', 'se')
        ->where('se.id = :id');

    $sub = $em ->createQueryBuilder();

    // Selectionner tous les stagiaires qui ne sont pas dans le résultat précédent
    $sub-> select('st')
        ->from('App\Entity\Stagiaire', 'st')
        ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
        ->setParameter('id', $sessionId)
        ->orderBy('st.nom');

    $query = $sub->getQuery();
    return $query->getResult();

    }

    public function findNonProgrammes($sessionId){

        $em = $this->getEntityManager();
        $sub = $em->CreateQueryBuilder();
    
        $qb = $sub;
        
        // Selectionner tous les modules d'un programme d'une session dont l'id est passé en paramètre
        $qb-> select('m')
            ->from('App\Entity\Modules', 'm')
            ->leftJoin('m.programmes', 'pr')
            ->where('pr.sessions = :id');
    
        $sub = $em ->createQueryBuilder();
    
        // Selectionner tous les modules qui ne sont pas dans le résultat précédent
        $sub-> select('mo')
            ->from('App\Entity\Modules', 'mo')
            ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
            ->setParameter('id', $sessionId)
            ->orderBy('mo.nomModule');
    
        $query = $sub->getQuery();
        return $query->getResult();
    
        }



//    /**
//     * @return Session[] Returns an array of Session objects
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

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
