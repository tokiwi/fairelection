<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection\Solver;

class SolverCoefficient
{
    private string $reference;
    private int $value;
    private object $object;

    public function __construct(string $reference, int $value, object $object)
    {
        $this->reference = $reference;
        $this->value = $value;
        $this->object = $object;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getObject(): object
    {
        return $this->object;
    }
}
