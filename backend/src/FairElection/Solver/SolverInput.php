<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection\Solver;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

class SolverInput
{
    private ArrayCollection $coefficients;

    /**
     * @Groups({"read"})
     */
    private array $constraints = [];

    public function __construct()
    {
        $this->coefficients = new ArrayCollection();
    }

    public function addCoefficient(SolverCoefficient $coefficient): self
    {
        $this->coefficients->forAll(function (int $index, SolverCoefficient $existingCoefficient) use ($coefficient) {
            if ($existingCoefficient->getReference() === $coefficient->getReference()) {
                throw new \LogicException(sprintf('The coefficient with reference %s already exists', $coefficient->getReference()));
            }

            return true;
        });

        $this->coefficients[] = $coefficient;

        return $this;
    }

    public function addConstraint(SolverConstraint $constraint): self
    {
        $this->constraints[] = $constraint;

        $constraint->setInput($this);

        return $this;
    }

    public function getCoefficientObjects(): ArrayCollection
    {
        return $this->coefficients;
    }

    /**
     * @Groups({"read"})
     */
    public function getCoefficients(): ArrayCollection
    {
        return $this->coefficients->map(fn (SolverCoefficient $c) => $c->getValue());
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function objectsFromResult(array $results): array
    {
        $i = 0;

        return $this->coefficients->map(function (SolverCoefficient $existingCoefficient) use ($results, &$i) {
            return [
                'object' => $existingCoefficient->getObject(),
                'is_elected' => (bool) $results[$i++],
            ];
        })->toArray();
    }
}
