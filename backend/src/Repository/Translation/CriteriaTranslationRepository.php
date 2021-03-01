<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\Translation;

use App\Entity\Criteria;
use App\Entity\Translation\CriteriaTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Criteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Criteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Criteria[]    findAll()
 * @method Criteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriteriaTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CriteriaTranslation::class);
    }
}
