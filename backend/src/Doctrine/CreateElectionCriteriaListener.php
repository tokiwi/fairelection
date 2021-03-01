<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use App\Entity\Criteria;
use App\Entity\Election;
use App\Entity\User;
use App\Repository\CriteriaRepository;
use DeepCopy\DeepCopy;
use DeepCopy\Filter\Doctrine\DoctrineCollectionFilter;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Filter\SetNullFilter;
use DeepCopy\Matcher\PropertyMatcher;
use DeepCopy\Matcher\PropertyNameMatcher;
use DeepCopy\Matcher\PropertyTypeMatcher;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Ulid;

class CreateElectionCriteriaListener
{
    private CriteriaRepository $criteriaRepository;
    private Security $security;

    public function __construct(CriteriaRepository $criteriaRepository, Security $security)
    {
        $this->criteriaRepository = $criteriaRepository;
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var Election $entity */
        $entity = $args->getEntity();

        if (!$entity instanceof Election) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $criterias = $this->criteriaRepository->findAllForAuthenticatedUser($user);

        $copier = new DeepCopy();
        $copier->addFilter(new SetNullFilter(), new PropertyNameMatcher('id'));
        $copier->addFilter(new SetNullFilter(), new PropertyMatcher(Criteria::class, 'election'));
        $copier->addFilter(new DoctrineCollectionFilter(), new PropertyTypeMatcher(Collection::class));
        $copier->addFilter(new ReplaceFilter(function () {
            return new Ulid();
        }), new PropertyNameMatcher('ulid'));

        /** @var Criteria $criteria */
        foreach ($criterias as $criteria) {
            /** @var Criteria $copy */
            $copy = $copier->copy($criteria);
            $copy->setElection($entity);
            $copy->setUser($user);

            $args->getEntityManager()->persist($copy);
        }
    }
}
