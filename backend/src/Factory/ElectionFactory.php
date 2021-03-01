<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Election;
use App\Repository\ElectionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static             Election|Proxy findOrCreate(array $attributes)
 * @method static             Election|Proxy random()
 * @method static             Election[]|Proxy[] randomSet(int $number)
 * @method static             Election[]|Proxy[] randomRange(int $min, int $max)
 * @method static             ElectionRepository|RepositoryProxy repository()
 * @method Election|Proxy     create($attributes = [])
 * @method Election[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ElectionFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'name' => 'Election name',
            'description' => 'Election description',
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Election $election) {})
        ;
    }

    protected static function getClass(): string
    {
        return Election::class;
    }
}
