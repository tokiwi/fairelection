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
class SolverResource
{
    /**
     * @ApiProperty(identifier=true)
     */
    public int $id = 0;

    public array $result;
}
