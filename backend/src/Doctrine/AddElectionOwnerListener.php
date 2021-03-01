<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use App\Entity\Election;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class AddElectionOwnerListener
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var Election $entity */
        $entity = $args->getEntity();

        if (!$entity instanceof Election) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User || null !== $entity->getOwner()) {
            return;
        }

        $entity->setOwner($user);
    }
}
