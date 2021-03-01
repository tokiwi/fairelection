<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use App\Entity\Criteria;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class AddCriteriaOwnerListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var Criteria $entity */
        $entity = $args->getEntity();

        if (!$entity instanceof Criteria) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        $entity->setUser($user);
    }
}
