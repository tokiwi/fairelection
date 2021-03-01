<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriteriaItemRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\CriteriaItemTranslation")
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('CRITERIA_ITEM_VIEW', object)"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=CriteriaItemRepository::class)
 */
class CriteriaItem
{
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"criteria:collection:read", "criteria:collection:write", "criteria:item:write", "election:item:read"})
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Gedmo\Translatable()
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"criteria:collection:read", "criteria:collection:write", "criteria:item:write", "election:item:read"})
     * @Assert\NotBlank()
     * @Assert\Length(max="5")
     * @Gedmo\Translatable()
     */
    private ?string $acronym;

    /**
     * @ORM\ManyToOne(targetEntity=Criteria::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Criteria $criteria;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): self
    {
        $this->acronym = $acronym;

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
}
