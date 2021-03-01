<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static         User|Proxy findOrCreate(array $attributes)
 * @method static         User|Proxy random()
 * @method static         User[]|Proxy[] randomSet(int $number)
 * @method static         User[]|Proxy[] randomRange(int $min, int $max)
 * @method static         UserRepository|RepositoryProxy repository()
 * @method User|Proxy     create($attributes = [])
 * @method User[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'plainPassword' => 'password',
            'fullName' => sprintf('%s %s', self::faker()->firstName, self::faker()->lastName),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(User $user) {})
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
