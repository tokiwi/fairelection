<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\ResultRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isElected;

    /**
     * @ORM\ManyToOne(targetEntity=Election::class, inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Election $election;

    /**
     * @ORM\OneToOne(targetEntity=Candidate::class, inversedBy="result", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Candidate $candidate;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
    }

    public function getIsElected(): ?bool
    {
        return $this->isElected;
    }

    public function setIsElected(bool $isElected): self
    {
        $this->isElected = $isElected;

        return $this;
    }

    public function getElection(): ?Election
    {
        return $this->election;
    }

    public function setElection(?Election $election): self
    {
        $this->election = $election;

        return $this;
    }

    public function getCandidate(): Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(Candidate $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }
}
