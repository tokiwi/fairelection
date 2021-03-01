<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\ElectionCriteria;
use App\Repository\ElectionCriteriaRepository;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static                     ElectionCriteria|Proxy findOrCreate(array $attributes)
 * @method static                     ElectionCriteria|Proxy random()
 * @method static                     ElectionCriteria[]|Proxy[] randomSet(int $number)
 * @method static                     ElectionCriteria[]|Proxy[] randomRange(int $min, int $max)
 * @method static                     ElectionCriteriaRepository|RepositoryProxy repository()
 * @method ElectionCriteria|Proxy     create($attributes = [])
 * @method ElectionCriteria[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ElectionCriteriaFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'ulid' => new Ulid(),
            'election' => ElectionFactory::new(),
            'criteria' => CriteriaFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(ElectionCriteria $electionCriteria) {})
        ;
    }

    protected static function getClass(): string
    {
        return ElectionCriteria::class;
    }
}
