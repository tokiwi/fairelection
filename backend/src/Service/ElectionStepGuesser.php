<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Candidate;
use App\Entity\Election;

class ElectionStepGuesser
{
    public function guessStep(Election $election): int
    {
        if (0 < $election->getResults()->count()) {
            return 7;
        }

        $candidatesWithVotes = $election->getCandidates()->filter(function (Candidate $candidate) {
            return null !== $candidate->getNumberOfVotes();
        });

        $count = $election->getCandidates()->count();
        if (0 < $count && $count === $candidatesWithVotes->count()) {
            return 6;
        }

        if (0 < $election->getCandidates()->count()) {
            return 5;
        }

        if (0 < $election->getNumberOfPeopleToElect()) {
            return 3;
        }

        if (0 < $election->getElectionCriterias()->count()) {
            return 2;
        }

        return 0;
    }
}
