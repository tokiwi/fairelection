<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Traits;

use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

trait UlidEntityTrait
{
    /**
     * @ApiProperty(identifier=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ApiProperty(identifier=true)
     * @ORM\Column(type="ulid", unique=true)
     */
    private Ulid $ulid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function setUlid(Ulid $ulid): void
    {
        $this->ulid = $ulid;
    }
}
