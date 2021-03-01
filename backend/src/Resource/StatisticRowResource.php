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

class StatisticRowResource
{
    /**
     * @Groups({"statisticresource:read"})
     */
    public string $categoryName;

    /**
     * @Groups({"statisticresource:read"})
     */
    public string $categoryPictogram;

    /**
     * @var StatisticItemResource[]
     *
     * @Groups({"statisticresource:read"})
     */
    public array $items = [];

    /**
     * @ApiProperty(identifier=true)
     */
    public function getId(): int
    {
        return 0;
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function hasErrors(): bool
    {
        $hasErrors = false;

        foreach ($this->items as $item) {
            if (!$item->isSufficient()) {
                $hasErrors = true;
                break;
            }
        }

        return $hasErrors;
    }

    public function addItem(StatisticItemResource $item): self
    {
        if (!\in_array($item, $this->items, true)) {
            $this->items[] = $item;
            $item->setStatisticRowResource($this);
        }

        return $this;
    }
}
