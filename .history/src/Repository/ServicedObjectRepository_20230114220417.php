<?php

namespace App\Repository;

use App\Entity\ServicedObject;
use App\Entity\File;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServicedObject>
 *
 * @method ServicedObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServicedObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServicedObject[]    findAll()
 * @method ServicedObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicedObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServicedObject::class, File::class);
    }

    public function save(ServicedObject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServicedObject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ServicedObject[] Returns an array of ServicedObject objects
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

//    public function findOneBySomeField($value): ?ServicedObject
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
