<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->userData() as [$email, $fullName]) {
            UserFactory::new()->create([
                'email' => $email,
                'fullName' => $fullName,
            ]);
        }
    }

    private function userData(): array
    {
        return [
            ['jean@fairelection.ch', 'Jean Dupont'],
        ];
    }
}
