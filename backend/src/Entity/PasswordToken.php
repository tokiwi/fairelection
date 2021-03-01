<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class PasswordToken extends AbstractPasswordToken
{
    /**
     * @var ?int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
}
