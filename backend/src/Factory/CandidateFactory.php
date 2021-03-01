<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static              Candidate|Proxy findOrCreate(array $attributes)
 * @method static              Candidate|Proxy random()
 * @method static              Candidate[]|Proxy[] randomSet(int $number)
 * @method static              Candidate[]|Proxy[] randomRange(int $min, int $max)
 * @method static              CandidateRepository|RepositoryProxy repository()
 * @method Candidate|Proxy     create($attributes = [])
 * @method Candidate[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CandidateFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Candidate $candidate) {})
        ;
    }

    protected static function getClass(): string
    {
        return Candidate::class;
    }
}
