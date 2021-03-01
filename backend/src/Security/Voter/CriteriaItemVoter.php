<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Voter;

use App\Entity\Criteria;
use App\Entity\CriteriaItem;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CriteriaItemVoter extends Voter
{
    private const VIEW = 'CRITERIA_ITEM_VIEW';

    protected function supports($attribute, $subject): bool
    {
        return self::VIEW === $attribute && $subject instanceof Criteria;
    }

    /**
     * @param CriteriaItem $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (null === $subject->getCriteria()) {
            return false;
        }

        // no user => every one can view
        if (null === $subject->getCriteria()->getUser()) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $subject->getCriteria()->getUser()->getId() === $user->getId();
    }
}
