<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\CriteriaItem;
use App\Repository\CriteriaItemRepository;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static                 CriteriaItem|Proxy findOrCreate(array $attributes)
 * @method static                 CriteriaItem|Proxy random()
 * @method static                 CriteriaItem[]|Proxy[] randomSet(int $number)
 * @method static                 CriteriaItem[]|Proxy[] randomRange(int $min, int $max)
 * @method static                 CriteriaItemRepository|RepositoryProxy repository()
 * @method CriteriaItem|Proxy     create($attributes = [])
 * @method CriteriaItem[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class CriteriaItemFactory extends ModelFactory
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
            'acronym' => sprintf('C%d', $randomNumber),
            'criteria' => CriteriaFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(CriteriaItem $criteriaItem) {})
        ;
    }

    protected static function getClass(): string
    {
        return CriteriaItem::class;
    }
}
