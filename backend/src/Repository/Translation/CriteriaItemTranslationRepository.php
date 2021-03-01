<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\Translation;

use App\Entity\CriteriaItem;
use App\Entity\Translation\CriteriaItemTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CriteriaItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CriteriaItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CriteriaItem[]    findAll()
 * @method CriteriaItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriteriaItemTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CriteriaItemTranslation::class);
    }
}
