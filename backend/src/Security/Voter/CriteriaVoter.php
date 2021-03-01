<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Criteria;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CriteriaVoter extends Voter
{
    private const VIEW = 'CRITERIA_VIEW';
    private const EDIT = 'CRITERIA_EDIT';

    protected function supports($attribute, $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT], false) && $subject instanceof Criteria;
    }

    /**
     * @param Criteria $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // no user => every one can view
        if (null === $subject->getUser()) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $subject->getUser()->getId() === $user->getId();
    }
}
