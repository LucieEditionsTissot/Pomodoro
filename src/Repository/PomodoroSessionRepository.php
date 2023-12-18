<?php

namespace App\Repository;

use App\Entity\PomodoroSession;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PomodoroSession>
 *
 * @method PomodoroSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method PomodoroSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method PomodoroSession[]    findAll()
 * @method PomodoroSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PomodoroSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PomodoroSession::class);
    }



    public function findCurrentSessionForUser(User $user): ?PomodoroSession
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'active')
            ->orderBy('p.startTime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0] ?? null;
    }

//    /**
//     * @return PomodoroSession[] Returns an array of PomodoroSession objects
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

//    public function findOneBySomeField($value): ?PomodoroSession
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
