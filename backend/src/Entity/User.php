<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use App\Traits\UlidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "register"={
 *              "method"="post",
 *              "path"="/register",
 *              "validation_groups"={"Default", "userRegister"}
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('USER_VIEW', object)"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="app_user")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    use TimestampableEntity;
    use UlidEntityTrait;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max={255})
     * @Groups({"user:read", "user:collection:write"})
     */
    private string $email;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @Assert\NotBlank(groups={"userRegister"})
     * @Assert\NotCompromisedPassword(groups={"userRegister"})
     * @Assert\Length(min="6", max="4096")
     * @SerializedName("password")
     * @Groups({"user:collection:write"})
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="string")
     * @Groups({"user:read", "user:write"})
     */
    private string $role = 'ROLE_USER';

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:collection:write"})
     */
    private string $fullName;

    /**
     * @var Collection|Criteria[]
     *
     * @ORM\OneToMany(targetEntity=Criteria::class, mappedBy="user")
     */
    private Collection $criterias;

    /**
     * @var Collection|Election[]
     *
     * @ORM\OneToMany(targetEntity=Election::class, mappedBy="owner")
     */
    private Collection $elections;

    public function __construct(string $ulid = null)
    {
        $this->ulid = null === $ulid ? new Ulid() : Ulid::fromString($ulid);
        $this->criterias = new ArrayCollection();
        $this->elections = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getRoles()
    {
        $roles[] = $this->role;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
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
            $criteria->setUser($this);
        }

        return $this;
    }

    public function removeCriteria(Criteria $criteria): self
    {
        // set the owning side to null (unless already changed)
        if ($this->criterias->removeElement($criteria) && $criteria->getUser() === $this) {
            $criteria->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection|Election[]
     */
    public function getElections(): Collection
    {
        return $this->elections;
    }

    public function addElection(Election $election): self
    {
        if (!$this->elections->contains($election)) {
            $this->elections[] = $election;
            $election->setOwner($this);
        }

        return $this;
    }

    public function removeElection(Election $election): self
    {
        // set the owning side to null (unless already changed)
        if ($this->elections->removeElement($election) && $election->getOwner() === $this) {
            $election->setOwner(null);
        }

        return $this;
    }
}
