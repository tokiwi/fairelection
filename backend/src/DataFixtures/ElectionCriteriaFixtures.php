<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Factory\ElectionCriteriaFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class ElectionCriteriaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->electionCriteriaData() as [$ulid, $election, $criteria]) {
            ElectionCriteriaFactory::new([
                'ulid' => Ulid::fromString($ulid),
                'election' => $this->getReference($election),
                'criteria' => $this->getReference($criteria),
            ])->create();
        }
    }

    private function electionCriteriaData(): array
    {
        return [
            ['01EVY2W69NHNPKCBPZ5VR5320H', '01EVXT7ZW43FKMTX52VB03R4MY', '01EVXSWVFEW54FJA7H15ZKN0R0'],
            ['01EVY2W69Z5GBKVVSJ5TJ12TC6', '01EVXT7ZW43FKMTX52VB03R4MY', '01EVXSWVHR6VXRYG7MGC9RXKEJ'],
            ['01EVY2W6A1ZMQ5GMV7BPX058GF', '01EVXT7ZW43FKMTX52VB03R4MY', '01EVXSWVJH09RFKWSYNSXXV2P1'],
        ];
    }

    public function getDependencies(): array
    {
        return [
            ElectionFixtures::class,
            CriteriaFixtures::class,
        ];
    }
}
