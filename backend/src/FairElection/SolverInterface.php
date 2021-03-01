<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection;

use App\FairElection\Solver\SolverInput;

interface SolverInterface
{
    public function solve(SolverInput $input): array;
}
