<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection\Solver;

use Symfony\Component\Serializer\Annotation\Groups;

class SolverConstraint
{
    public const OPERATOR_EQUAL = '==';
    public const OPERATOR_GREATER_OR_EQUAL = '>=';

    private SolverInput $input;

    /**
     * @Groups({"read"})
     */
    private array $variables = [];

    /**
     * @Groups({"read"})
     */
    private string $operator;

    /**
     * @Groups({"read"})
     */
    private int $value;

    public function __construct(string $operator, int $value)
    {
        $this->operator = $operator;
        $this->value = $value;
    }

    public function addVariable(string $coefficientReference): self
    {
        $elements = $this->input->getCoefficientObjects()->filter(fn (SolverCoefficient $c) => $c->getReference() === $coefficientReference);

        $this->variables[] = $this->input->getCoefficientObjects()->indexOf($elements->first());

        return $this;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setInput(SolverInput $input): self
    {
        $this->input = $input;

        return $this;
    }
}
