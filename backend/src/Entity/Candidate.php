<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CandidateRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post"={
 *              "security"="is_granted('ROLE_USER')"
 *          },
 *          "candidate_collection_votes"={
 *              "method"="post",
 *              "path"="/candidates/votes",
 *              "security"="is_granted('ROLE_USER')",
 *              "output"=false
 *          },
 *          "get"={
 *              "paginationEnabled"=false
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('CANDIDATE_VIEW', object)"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 * @ApiResource(attributes={"order"={"position": "ASC"}})
 */
class Candidate
{
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"candidate:collection:write", "candidate:collection:read"})
     * @Assert\NotBlank()
     * @Assert\Length(max={255})
     */
    private string $name;

    /**
     * @var Collection|Assignment[]
     *
     * @ORM\ManyToMany(targetEntity=Assignment::class, mappedBy="candidates")
     */
    private Collection $assignments;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"candidate:collection:write", "candidate:collection:read"})
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\LessThan(value="100000")
     */
    private ?int $numberOfVotes = null;

    /**
     * @Gedmo\SortableGroup()
     * @ORM\ManyToOne(targetEntity=Election::class, inversedBy="candidates")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Election $election;

    /**
     * @ORM\OneToOne(targetEntity=Result::class, mappedBy="candidate", cascade={"persist", "remove"})
     */
    private ?Result $result;

    /**
     * @ORM\Column(type="integer")
     * @Gedmo\SortablePosition()
     */
    private int $position;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->assignments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $assignment->addCandidate($this);
        }

        return $this;
    }

    public function removeAssignment(Assignment $assignment): self
    {
        if ($this->assignments->removeElement($assignment)) {
            $assignment->removeCandidate($this);
        }

        return $this;
    }

    public function getNumberOfVotes(): ?int
    {
        return $this->numberOfVotes;
    }

    public function setNumberOfVotes(int $numberOfVotes): self
    {
        $this->numberOfVotes = $numberOfVotes;

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

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(Result $result): self
    {
        // set the owning side of the relation if necessary
        if ($result->getCandidate() !== $this) {
            $result->setCandidate($this);
        }

        $this->result = $result;

        return $this;
    }

    public function removeAssignments(): self
    {
        foreach ($this->assignments as $a) {
            $a->removeCandidate($this);
            $this->assignments->removeElement($a);
        }

        return $this;
    }

    public function hasAssignmentForItem(CriteriaItem $item): bool
    {
        return $this->assignments->exists(function (int $index, Assignment $assignment) use ($item) {
            return $assignment->getItem() === $item;
        });
    }
}
