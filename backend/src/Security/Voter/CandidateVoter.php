<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Candidate;
use App\Entity\Election;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CandidateVoter extends Voter
{
    private const VIEW = 'CANDIDATE_VIEW';

    protected function supports($attribute, $subject): bool
    {
        return self::VIEW === $attribute && $subject instanceof Election;
    }

    /**
     * @param Candidate $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (null === $subject->getElection() || null === $subject->getElection()->getOwner()) {
            return false;
        }

        return $subject->getElection()->getOwner()->getId() === $user->getId();
    }
}
