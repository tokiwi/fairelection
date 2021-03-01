<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Election;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post"
 *     },
 *     itemOperations={
 *          "get"
 *     }
 * )
 */
class CandidateResource
{
    /**
     * @Groups({"candidateresource:write", "candidateresource:read"})
     */
    public array $candidates = [];

    /**
     * @Groups({"candidateresource:write"})
     */
    public Election $election;

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId(): Ulid
    {
        return new Ulid();
    }
}
