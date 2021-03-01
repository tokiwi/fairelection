<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resource;

use Symfony\Component\Serializer\Annotation\Groups;

class StatisticItemResource
{
    private string $category;

    private int $candidateNumber;

    private int $target;

    private int $totalCandidates;

    private StatisticRowResource $statisticRowResource;

    public function __construct(int $candidateNumber, string $category, int $target, int $totalCandidates)
    {
        $this->candidateNumber = $candidateNumber;
        $this->category = $category;
        $this->target = $target;
        $this->totalCandidates = $totalCandidates;
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function getCandidateNumber(): int
    {
        return $this->candidateNumber;
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function getPercent(): int
    {
        return (int) round($this->candidateNumber * 100 / $this->totalCandidates);
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function isSufficient(): bool
    {
        return round($this->target * 100 / $this->totalCandidates) <= $this->getPercent();
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function getRed(): int
    {
        return (int) round($this->target * 100 / $this->totalCandidates);
    }

    /**
     * @Groups({"statisticresource:read"})
     */
    public function getGreen(): int
    {
        return 100 - $this->getRed();
    }

    public function setStatisticRowResource(StatisticRowResource $statisticRowResource): self
    {
        $this->statisticRowResource = $statisticRowResource;

        return $this;
    }
}
