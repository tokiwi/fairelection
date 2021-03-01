<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Election;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ElectionVoter extends Voter
{
    private const VIEW = 'ELECTION_VIEW';
    private const EDIT = 'ELECTION_EDIT';
    private const DELETE = 'ELECTION_DELETE';

    protected function supports($attribute, $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], false) && $subject instanceof Election;
    }

    /**
     * @param Election $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // no owner => every one can view
        if (self::VIEW === $attribute && null === $subject->getOwner()) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (null === $subject->getOwner()) {
            return false;
        }

        return $subject->getOwner()->getId() === $user->getId();
    }
}
