<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\CriteriaTranslation")
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_USER')"
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *            "security"="is_granted('CRITERIA_VIEW', object)"
 *         },
 *         "put"={
 *            "security"="is_granted('CRITERIA_EDIT', object)"
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CriteriaRepository")
 */
class Criteria
{
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="string")
     * @Groups({"criteria:collection:read", "election:collection:read", "election:item:read", "criteria:collection:write", "criteria:item:write"})
     * @Assert\NotBlank()
     * @Assert\Length(max={255})
     * @Gedmo\Translatable()
     */
    private string $name;

    /**
     * @ORM\Column(type="string")
     * @Groups({"criteria:collection:read", "election:collection:read", "election:item:read", "criteria:collection:write", "criteria:item:write"})
     * @Assert\NotBlank()
     */
    private string $pictogram;

    /**
     * @var Collection|CriteriaItem[]
     *
     * @ORM\OneToMany(targetEntity=CriteriaItem::class, mappedBy="criteria", cascade={"persist", "remove"}, orphanRemoval=false)
     * @Groups({"criteria:collection:read", "criteria:collection:write", "criteria:item:write"})
     * @Assert\Count(min="1")
     * @Assert\Valid()
     */
    private Collection $items;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="criterias")
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Election::class, inversedBy="criterias")
     * @Groups({"criteria:collection:write"})
     */
    private ?Election $election;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->items = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPictogram(): string
    {
        return $this->pictogram;
    }

    public function setPictogram(string $pictogram): void
    {
        $this->pictogram = $pictogram;
    }

    /**
     * @return Collection|CriteriaItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CriteriaItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCriteria($this);
        }

        return $this;
    }

    public function removeItem(CriteriaItem $item): self
    {
        // set the owning side to null (unless already changed)
        if ($this->items->removeElement($item) && $item->getCriteria() === $this) {
            $item->setCriteria(null);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Groups({"criteria:collection:get"})
     */
    public function isEditable(): bool
    {
        return null !== $this->user;
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
}
