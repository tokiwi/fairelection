<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection\Solver;

use App\Entity\Candidate;
use App\Entity\Election;

class SolverInputFactory
{
    public static function createFromElection(Election $election): SolverInput
    {
        $input = new SolverInput();
        $constraint = new SolverConstraint(SolverConstraint::OPERATOR_EQUAL, $election->getNumberOfPeopleToElect()); // @phpstan-ignore-line
        $input->addConstraint($constraint);

        /** @var Candidate $candidate */
        foreach ($election->getCandidates() as $candidate) {
            $input->addCoefficient(new SolverCoefficient($candidate->getId(), $candidate->getNumberOfVotes() ?? 1, $candidate)); // @phpstan-ignore-line
            $constraint->addVariable($candidate->getId()); // @phpstan-ignore-line
        }

        foreach ($election->getElectionCriterias() as $electionCriteria) {
            foreach ($electionCriteria->getAssignments() as $assignment) {
                $constraint = new SolverConstraint(SolverConstraint::OPERATOR_GREATER_OR_EQUAL, $assignment->getPercent()); // @phpstan-ignore-line
                $input->addConstraint($constraint);

                foreach ($assignment->getCandidates() as $candidate) {
                    $constraint->addVariable($candidate->getId()); // @phpstan-ignore-line
                }
            }
        }

        return $input;
    }
}
