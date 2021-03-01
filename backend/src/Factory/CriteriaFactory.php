<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Criteria;
use App\Repository\CriteriaRepository;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static             Criteria|Proxy findOrCreate(array $attributes)
 * @method static             Criteria|Proxy random()
 * @method static             Criteria[]|Proxy[] randomSet(int $number)
 * @method static             Criteria[]|Proxy[] randomRange(int $min, int $max)
 * @method static             CriteriaRepository|RepositoryProxy repository()
 * @method Criteria|Proxy     create($attributes = [])
 * @method Criteria[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CriteriaFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $randomNumber = random_int(0, 10000);

        return [
            'ulid' => new Ulid(),
            'name' => sprintf('Criteria %d', $randomNumber),
            'pictogram' => sprintf('Pictogram %d', $randomNumber),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Criteria $criteria) {})
        ;
    }

    protected static function getClass(): string
    {
        return Criteria::class;
    }
}
