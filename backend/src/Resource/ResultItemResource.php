<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

class ResultItemResource
{
    /**
     * @Groups({"resultresource:read"})
     */
    public string $candidate;

    /**
     * @Groups({"resultresource:read"})
     */
    public ?int $votes;

    /**
     * @Groups({"resultresource:read"})
     */
    public ?bool $isElected = false;

    /**
     * @Groups({"resultresource:read"})
     */
    public array $criterias = [];

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId(): Ulid
    {
        return new Ulid();
    }
}
