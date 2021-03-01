<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Election;
use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    /**
     * @return array|Result[]
     */
    public function findAllByElectionUlid(AbstractUid $ulid): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('c')
            ->addSelect('a')
            ->leftJoin('r.election', 'e')
            ->leftJoin('r.candidate', 'c')
            ->leftJoin('c.assignments', 'a')
            ->where('e.ulid = :ulid')
            ->setParameter('ulid', $ulid, 'ulid')
            ->orderBy('c.numberOfVotes', 'DESC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function deleteAllByElection(Election $election): void
    {
        $this->createQueryBuilder('r')
            ->delete()
            ->where('r.election = :election')
            ->setParameter('election', $election)
            ->getQuery()
            ->execute()
        ;
    }
}
