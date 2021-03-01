<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\User;
use App\Resource\ResultResource;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ResultResourceVoter extends Voter
{
    private const VIEW = 'RESULT_RESOURCE_VIEW';

    protected function supports($attribute, $subject): bool
    {
        return self::VIEW === $attribute && $subject instanceof ResultResource;
    }

    /**
     * @param ResultResource $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (null === $subject->election->getOwner()) {
            return false;
        }

        return $subject->election->getOwner()->getId() === $user->getId();
    }
}
