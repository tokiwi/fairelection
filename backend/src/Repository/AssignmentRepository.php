<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Assignment;
use App\Entity\Election;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @method Assignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assignment[]    findAll()
 * @method Assignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assignment::class);
    }

    public function statisticsByElection(AbstractUid $ulid, bool $electedOnly = false): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('c.name AS criteriaName')
            ->addSelect('c.pictogram AS criteriaPictogram')
            ->addSelect('i.acronym AS itemAcronym')
            ->addSelect('a.percent AS target')
            ->addSelect('COUNT(ca.id) AS candidateNumber')
            ->leftJoin('a.item', 'i')
            ->leftJoin('a.electionCriteria', 'ec')
            ->leftJoin('ec.criteria', 'c')
            ->leftJoin('ec.election', 'e')
            ->leftJoin('a.candidates', 'ca')
            ->andWhere('e.ulid = :election_ulid')
            ->setParameter('election_ulid', $ulid, 'ulid')
            ->groupBy('i.id')
        ;

        if ($electedOnly) {
            $qb->leftJoin('ca.result', 'r')
                ->andWhere('r.isElected = 1');
        }

        return $qb->getQuery()
            ->getArrayResult()
        ;
    }

    public function deleteAllByElection(Election $election): void
    {
        $ids = $this->createQueryBuilder('a')
            ->leftJoin('a.candidates', 'c')
            ->andWhere('c.election = :election')
            ->setParameter('election', $election)
            ->select('a.id')
            ->getQuery()
            ->getResult();

        $this->createQueryBuilder('a')
            ->leftJoin('a.candidates', 'c')
            ->delete()
            ->andWhere('a.id in (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute()
        ;
    }

//    public function test(AbstractUid $ulid): array
//    {
//        return $this->createQueryBuilder('a')
//            ->addSelect('i.acronym AS itemAcronym')
//            ->addSelect('a.percent AS target')
//            ->addSelect('COUNT(ca.id) AS candidateNumber')
//            ->leftJoin('a.item', 'i')
//            ->leftJoin('a.electionCriteria', 'ec')
//            ->leftJoin('ec.criteria', 'c')
//            ->leftJoin('ec.election', 'e')
//            ->leftJoin('a.candidates', 'ca')
//            ->andWhere('e.ulid = :election_ulid')
//            ->setParameter('election_ulid', $ulid, 'ulid')
//            ->groupBy('i.id')
//            ->getQuery()
//            ->getResult()
//            ;
//    }
}
