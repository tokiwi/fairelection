<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Factory\ElectionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Proxy;

class ElectionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->criteriaData() as [$ulid, $name]) {
            /** @var Proxy $election */
            $election = ElectionFactory::new()->create([
                'ulid' => Ulid::fromString($ulid),
                'name' => $name,
            ]);

            $this->addReference($ulid, $election->object());
        }
    }

    private function criteriaData(): array
    {
        return [
            ['01EVXT7ZW43FKMTX52VB03R4MY', 'Elections communales Martigny'],
        ];
    }
}
