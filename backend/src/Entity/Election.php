<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DownloadCandidateModelAction;
use App\Controller\DownloadVoteModelAction;
use App\Controller\UploadCandidateModelAction;
use App\Controller\UploadVoteModelAction;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post",
 *          "get"={
 *              "security"="is_granted('ROLE_USER')"
 *          },
 *          "collection_election_candidate_model"={
 *              "method"="post",
 *              "path"="elections/candidate-model",
 *              "deserialize"=false,
 *              "controller"=UploadCandidateModelAction::class
 *          },
 *          "collection_election_vote_model"={
 *              "method"="post",
 *              "path"="elections/vote-model",
 *              "deserialize"=false,
 *              "controller"=UploadVoteModelAction::class
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('ELECTION_VIEW', object)"
 *          },
 *          "put"={
 *              "security"="is_granted('ELECTION_EDIT', object)"
 *          },
 *          "delete"={
 *              "security"="is_granted('ELECTION_DELETE', object)"
 *          },
 *          "item_election_candidate_model"={
 *              "method"="get",
 *              "path"="elections/{ulid}/candidate-model",
 *              "controller"=DownloadCandidateModelAction::class,
 *              "security"="is_granted('ELECTION_VIEW', object)"
 *          },
 *          "item_election_vote_model"={
 *              "method"="get",
 *              "path"="elections/{ulid}/vote-model",
 *              "controller"=DownloadVoteModelAction::class,
 *              "security"="is_granted('ELECTION_VIEW', object)"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ElectionRepository")
 */
class Election
{
    use TimestampableEntity;
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="string")
     * @Groups({"election:item:read", "election:collection:read", "election:write"})
     * @Assert\Length(max={255})
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"election:write", "election:collection:read"})
     * @Assert\Length(max={2000})
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(min="1", max="1000")
     * @Groups({"election:item:read", "election:write"})
     */
    private ?int $numberOfPeopleToElect;

    /**
     * @var Collection|ElectionCriteria[]
     *
     * @ORM\OneToMany(targetEntity=ElectionCriteria::class, mappedBy="election", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @Groups({"election:item:read", "election:collection:read"})
     */
    private Collection $electionCriterias;

    /**
     * @var Collection|Candidate[]
     *
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="election", cascade={"remove"})
     */
    private Collection $candidates;

    /**
     * @var Collection|Result[]
     *
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="election", cascade={"remove"})
     */
    private Collection $results;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="elections")
     */
    private ?User $owner = null;

    /**
     * @var Collection|Criteria[]
     *
     * @ORM\OneToMany(targetEntity=Criteria::class, mappedBy="election", cascade={"remove"})
     */
    private Collection $criterias;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->electionCriterias = new ArrayCollection();
        $this->candidates = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->criterias = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumberOfPeopleToElect(): ?int
    {
        return $this->numberOfPeopleToElect;
    }

    public function setNumberOfPeopleToElect(?int $numberOfPeopleToElect): self
    {
        $this->numberOfPeopleToElect = $numberOfPeopleToElect;

        return $this;
    }

    /**
     * @return Collection|ElectionCriteria[]
     */
    public function getElectionCriterias(): Collection
    {
        return $this->electionCriterias;
    }

    public function addElectionCriteria(ElectionCriteria $electionCriteria): self
    {
        if (!$this->electionCriterias->contains($electionCriteria)) {
            $this->electionCriterias[] = $electionCriteria;
            $electionCriteria->setElection($this);
        }

        return $this;
    }

    public function removeElectionCriteria(ElectionCriteria $electionCriteria): self
    {
        // set the owning side to null (unless already changed)
        if ($this->electionCriterias->removeElement($electionCriteria) && $electionCriteria->getElection() === $this) {
            $electionCriteria->setElection(null);
        }

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
            $candidate->setElection($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        // set the owning side to null (unless already changed)
        if ($this->candidates->removeElement($candidate) && $candidate->getElection() === $this) {
            $candidate->setElection(null);
        }

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setElection($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        // set the owning side to null (unless already changed)
        if ($this->results->removeElement($result) && $result->getElection() === $this) {
            $result->setElection(null);
        }

        return $this;
    }

    public function removeResults(): self
    {
        foreach ($this->results as $r) {
            //$r->setElection(null);
            $this->results->removeElement($r);
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @Groups({"election:collection:read"})
     */
    public function getHasResults(): bool
    {
        return 0 < $this->results->count();
    }

    /**
     * @Groups({"election:collection:read"})
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection|Criteria[]
     */
    public function getCriterias(): Collection
    {
        return $this->criterias;
    }

    public function addCriteria(Criteria $criteria): self
    {
        if (!$this->criterias->contains($criteria)) {
            $this->criterias[] = $criteria;
            $criteria->setElection($this);
        }

        return $this;
    }

    public function removeCriteria(Criteria $criteria): self
    {
        // set the owning side to null (unless already changed)
        if ($this->criterias->removeElement($criteria) && $criteria->getElection() === $this) {
            $criteria->setElection(null);
        }

        return $this;
    }
}
