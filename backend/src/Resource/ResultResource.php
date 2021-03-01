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
 *     collectionOperations={},
 *     itemOperations={
 *          "get"={
 *              "security"="is_granted('RESULT_RESOURCE_VIEW', object)"
 *          }
 *     }
 * )
 */
class ResultResource
{
    /**
     * @Groups({"resultresource:read"})
     */
    public array $rows = [];

    /**
     * @Groups({"resultresource:read"})
     */
    public array $stats = [];

    public Election $election;

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId(): Ulid
    {
        return new Ulid();
    }
}
