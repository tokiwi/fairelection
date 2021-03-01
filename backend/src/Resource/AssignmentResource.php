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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "post"={
 *              "output"=false
 *          }
 *     },
 *     itemOperations={
 *          "get"
 *     }
 * )
 */
class AssignmentResource
{
    /**
     * @ApiProperty(identifier=true)
     * @Groups({"assignmentresource:write"})
     * @Assert\NotNull()
     */
    public string $election;

    /**
     * @Groups({"assignmentresource:write"})
     * @Assert\GreaterThanOrEqual(value="1")
     */
    public int $numberOfPeopleToElect;

    /**
     * The assignments percents as an array of [Assignment IRI => percent].
     * <code>
     * {
     *   "/assignments/01EWJ7M84010NWQJEYX1GP2YK8": 10,
     *   "/assignments/01EWJ7M84010NWQJEYX1GP2YKB": 55
     * }
     * </code>.
     *
     * @Groups({"assignmentresource:write"})
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Range(min="0", max="100")
     * })
     */
    public array $assignments;

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId(): Ulid
    {
        return new Ulid();
    }
}
