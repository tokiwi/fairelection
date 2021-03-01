<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Assignment;
use App\Repository\AssignmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static               Assignment|Proxy findOrCreate(array $attributes)
 * @method static               Assignment|Proxy random()
 * @method static               Assignment[]|Proxy[] randomSet(int $number)
 * @method static               Assignment[]|Proxy[] randomRange(int $min, int $max)
 * @method static               AssignmentRepository|RepositoryProxy repository()
 * @method Assignment|Proxy     create($attributes = [])
 * @method Assignment[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class AssignmentFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'electionCriteria' => ElectionCriteriaFactory::new(),
            'item' => CriteriaItemFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Assignment $assignment) {})
        ;
    }

    protected static function getClass(): string
    {
        return Assignment::class;
    }
}
