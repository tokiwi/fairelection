<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Election;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @method Election|null find($id, $lockMode = null, $lockVersion = null)
 * @method Election|null findOneBy(array $criteria, array $orderBy = null)
 * @method Election[]    findAll()
 * @method Election[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Election::class);
    }

    public function findOneByUlid(AbstractUid $ulid): Election
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.ulid = :ulid')
            ->setParameter('ulid', $ulid, 'ulid')
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
