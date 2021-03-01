<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AssignmentRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *          "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass=AssignmentRepository::class)
 */
class Assignment
{
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\GreaterThan(value="0")
     * @Groups({"assignmentresource:write", "election:item:read"})
     */
    private int $percent = 0;

    /**
     * @ORM\ManyToOne(targetEntity=ElectionCriteria::class, inversedBy="assignments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"assignment:collection:write"})
     * @Assert\NotNull()
     */
    private ?ElectionCriteria $electionCriteria;

    /**
     * @ORM\ManyToOne(targetEntity=CriteriaItem::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"assignment:collection:write", "election:item:read"})
     * @Assert\NotNull()
     */
    private ?CriteriaItem $item;

    /**
     * @ORM\ManyToMany(targetEntity=Candidate::class, inversedBy="assignments")
     */
    private $candidates;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->candidates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getElectionCriteria(): ?ElectionCriteria
    {
        return $this->electionCriteria;
    }

    public function setElectionCriteria(?ElectionCriteria $electionCriteria): self
    {
        $this->electionCriteria = $electionCriteria;

        return $this;
    }

    public function getItem(): ?CriteriaItem
    {
        return $this->item;
    }

    public function setItem(?CriteriaItem $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        $this->candidates->removeElement($candidate);

        return $this;
    }
}
