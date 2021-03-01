<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Candidate;
use App\Entity\Election;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\AbstractUid;

/**
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    private IriConverterInterface $iriConverter;

    public function __construct(ManagerRegistry $registry, IriConverterInterface $iriConverter)
    {
        parent::__construct($registry, Candidate::class);
        $this->iriConverter = $iriConverter;
    }

    public function findAllByOwner(User $owner, AbstractUid $ulid = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.assignments', 'a')
            ->leftJoin('a.electionCriteria', 'ec')
            ->leftJoin('ec.election', 'e')
            ->andWhere('e.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('c.position', 'ASC')
        ;

        if (null !== $ulid) {
            $qb->andWhere('e.ulid = :election_ulid')
                ->setParameter('election_ulid', $ulid, 'ulid');
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllByElection(AbstractUid $ulid): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('e')
            ->leftJoin('c.election', 'e')
            ->andWhere('e.ulid = :election_ulid')
            ->setParameter('election_ulid', $ulid, 'ulid')
            ->orderBy('c.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countTotalCandidateByElection(AbstractUid $ulid, bool $electedOnly = false): int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(DISTINCT c.id)')
            ->leftJoin('c.election', 'e')
            ->andWhere('e.ulid = :election_ulid')
            ->setParameter('election_ulid', $ulid, 'ulid')
        ;

        if ($electedOnly) {
            $qb->leftJoin('c.result', 'r')
                ->andWhere('r.isElected = 1');
        }

        return (int) $qb->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function deleteAllByElection(Election $election): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.election = :election')
            ->setParameter('election', $election)
            ->getQuery()
            ->execute()
        ;
    }

    public function removeAllNotInIri(Election $election, array $iris = []): void
    {
        $em = $this->getEntityManager();

        foreach ($election->getCandidates() as $candidate) {
            if (!\in_array($this->iriConverter->getIriFromItem($candidate), $iris, true)) {
                $em->remove($candidate);
            }
        }
    }
}
