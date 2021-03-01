<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Factory\CriteriaFactory;
use App\Factory\CriteriaItemFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Proxy;

class CriteriaFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->criteriaData() as [$ulid, $name, $pictogram, $criteriaItems]) {
            /** @var Proxy $criteria */
            $criteria = CriteriaFactory::new([
                'ulid' => Ulid::fromString($ulid),
                'name' => $name,
                'pictogram' => $pictogram,
            ])->create();

            $this->setReference($ulid, $criteria->object());

            foreach ($criteriaItems as [$itemUlid, $itemName, $itemAcronym]) {
                CriteriaItemFactory::new([
                    'ulid' => Ulid::fromString($itemUlid),
                    'criteria' => $criteria,
                    'name' => $itemName,
                    'acronym' => $itemAcronym,
                ])->create();
            }
        }
    }

    private function criteriaData(): array
    {
        return [
            ['01EVXSWVFEW54FJA7H15ZKN0R0', 'Gender', 'gender', [
                ['01EVY16DH1XSMB2TJ76TQNYV6Y', 'Man', 'M'],
                ['01EVY16DH3M30C2Y1T4EPCJK07', 'Woman', 'W'],
            ]],
            ['01EVXSWVH55S4MY8PXN74STK7D', 'Age', 'age', [
                ['01EVY16DJAJRQ2RFHBVDXMYMSA', 'Below 18 years old', '<18'],
                ['01EVY16DJD1KHB24QFJJVWK1JB', '18 years old or above', '>= 18'],
            ]],
            ['01EVXSWVHR6VXRYG7MGC9RXKEJ', 'Region', 'region', [
                ['01EVY16DHD652XRKXBKX3T9MS8', 'Vaud', 'VD'],
                ['01EVY16DHED9J22YQ3BHXZAF31', 'Valais', 'VS'],
                ['01EVY16DHGJMJT55NMZHA6XP5W', 'Genève', 'GE'],
            ]],
            ['01EVXSWVJH09RFKWSYNSXXV2P1', 'Commitment', 'commitment', [
                ['01EVY16DHTBTEEXR60JZRDGFMB', 'Commitment 1', 'C1'],
                ['01EVY16DHWE6RCA220CFQZCSXM', 'Commitment 2', 'C2'],
                ['01EVY16DJ10A4D6MH4CTH2RP7F', 'Commitment 3', 'C3'],
                ['01EVY16DJ4BMCX5CA0RJEP172P', 'Commitment 4', 'C4'],
                ['01EVY16DJ7B746CR09HKA3G2JA', 'Commitment 5', 'C5'],
            ]],
        ];
    }
}
