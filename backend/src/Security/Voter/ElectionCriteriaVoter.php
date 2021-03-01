<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\ElectionCriteria;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ElectionCriteriaVoter extends Voter
{
    private const VIEW = 'ELECTION_CRITERIA_VIEW';
    private const EDIT = 'ELECTION_CRITERIA_DELETE';

    protected function supports($attribute, $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT], false) && $subject instanceof ElectionCriteria;
    }

    /**
     * @param ElectionCriteria $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (null === $subject->getElection()) {
            return false;
        }

        // no user => every one can view
        if (null === $subject->getElection()->getOwner()) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $subject->getElection()->getOwner()->getId() === $user->getId();
    }
}
