<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getId(int $id)
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'SELECT * FROM annonce a
            WHERE a.id=:id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

    public function getAllAnnonce()
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'SELECT annonce.id, annonce.title, annonce.description, annonce.price, annonce.created_at, annonce.updated_at, categorie.cat_title, user.email FROM `annonce`
        LEFT JOIN categorie ON annonce.categorie_id =categorie.id
        LEFT JOIN user ON annonce.user_id =user.id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

    public function verifyUser(int $id)
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'SELECT user.id FROM annonce
        LEFT JOIN user ON annonce.user_id = user.id
        WHERE annonce.id=:id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

    public function supprimer(int $id)
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'DELETE FROM annonce
        WHERE annonce.id=:id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

    public function annonceWithId(int $id)
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'SELECT * FROM annonce a
            LEFT JOIN user u ON a.user_id = u.id
            LEFT JOIN categorie c ON a.categorie_id = c.id
            WHERE a.id=:id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

    public function annonceOfUser(int $id)
    {
        // avec les requetes SQL
        $conn = $this->getEntityManager()->getConnection();

        $sql= 'SELECT annonce.id, annonce.title, annonce.description, annonce.price, annonce.created_at, annonce.updated_at, categorie.cat_title, user.email FROM annonce
            LEFT JOIN user ON annonce.user_id = user.id
            LEFT JOIN categorie ON annonce.categorie_id = categorie.id
            WHERE user.id=:id';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        $finalResult = $resultSet->fetchAllAssociative();
        
        return $finalResult;
    }

//    /**
//     * @return Annonce[] Returns an array of Annonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
