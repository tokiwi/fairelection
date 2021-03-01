<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ElectionCriteriaRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post"
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ELECTION_CRITERIA_VIEW', object)"
 *          },
 *          "delete"={
 *              "security"="is_granted('ELECTION_CRITERIA_DELETE', object)"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ElectionCriteriaRepository::class)
 */
class ElectionCriteria
{
    use UlidEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Election::class, inversedBy="electionCriterias")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"electioncriteria:collection:write"})
     */
    private ?Election $election;

    /**
     * @ORM\ManyToOne(targetEntity=Criteria::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"electioncriteria:collection:write", "election:collection:read", "election:item:read"})
     */
    private ?Criteria $criteria = null;

    /**
     * @var Collection|Assignment[]
     *
     * @ORM\OneToMany(targetEntity=Assignment::class, mappedBy="electionCriteria", cascade={"persist", "remove"})
     * @Groups({"election:item:read", "assignmentresource:write"})
     */
    private Collection $assignments;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->assignments = new ArrayCollection();
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

    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    public function setCriteria(?Criteria $criteria): self
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return Collection|Assignment[]
     */
    public function getAssignments(): Collection
    {
        return $this->assignments;
    }

    public function addAssignment(Assignment $assignment): self
    {
        if (!$this->assignments->contains($assignment)) {
            $this->assignments[] = $assignment;
            $assignment->setElectionCriteria($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->assignments->removeElement($assignment) && $assignment->getElectionCriteria() === $this) {
            $assignment->setElectionCriteria(null);
        }

        return $this;
    }
}
