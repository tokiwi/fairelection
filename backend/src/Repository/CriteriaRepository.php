<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Criteria;
use App\Entity\Election;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Criteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Criteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Criteria[]    findAll()
 * @method Criteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Criteria::class);
    }

    /**
     * @return array|Criteria[]
     */
    public function findAllByElectionAndUser(Election $election, User $user = null): array
    {
        $qb = $this->createQueryBuilder('c');

        if (null !== $user) {
            $qb->andWhere('(c.election = :election AND c.user = :user)')
                ->setParameter('user', $user);
        }

        return $qb
            ->orWhere('(c.election = :election AND c.user IS NULL)')
            ->setParameter('election', $election)
            ->orderBy('c.user', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return array|Criteria[]
     */
    public function findAllForAuthenticatedUser(UserInterface $user = null): array
    {
        $qb = $this->createQueryBuilder('c');

        if (null !== $user) {
            $qb->orWhere('c.user = :user')

                ->setParameter('user', $user);
        }

        return $qb
            ->orWhere('c.user IS NULL')
            ->andWhere('c.election IS NULL')
            ->orderBy('c.user', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
