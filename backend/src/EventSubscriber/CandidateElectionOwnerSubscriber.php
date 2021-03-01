<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Resource\CandidateResource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class CandidateElectionOwnerSubscriber implements EventSubscriberInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setElectionOwner', EventPriorities::PRE_WRITE],
        ];
    }

    public function setElectionOwner(ViewEvent $event): void
    {
        /** @var CandidateResource $candidateResource */
        $candidateResource = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$candidateResource instanceof CandidateResource || Request::METHOD_POST !== $method) {
            return;
        }

        $election = $candidateResource->election;

        $user = $this->security->getUser();
        if (!$user instanceof User || null !== $election->getOwner()) {
            return;
        }

        $election->setOwner($user);
    }
}
