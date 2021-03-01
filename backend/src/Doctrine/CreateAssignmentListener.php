<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use App\Entity\Assignment;
use App\Entity\ElectionCriteria;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CreateAssignmentListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var ElectionCriteria $entity */
        $entity = $args->getEntity();

        if (!$entity instanceof ElectionCriteria) {
            return;
        }

        if (null === $entity->getCriteria()) {
            return;
        }

        foreach ($entity->getCriteria()->getItems() as $item) {
            $assignment = new Assignment();
            $assignment->setItem($item);

            $entity->addAssignment($assignment);
        }
    }
}
