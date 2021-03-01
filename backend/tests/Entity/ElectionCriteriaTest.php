<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CriteriaFactory;
use App\Factory\ElectionCriteriaFactory;
use App\Factory\ElectionFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Foundry\Test\Factories;

class ElectionCriteriaTest extends ApiTestCase
{
    use Factories;

    /**
     * @group functional
     */
    public function testCreateElectionCriteria(): void
    {
        $election = ElectionFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
        ]);

        $criteria = CriteriaFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXSWVFEW54FJA7H15ZKN0R0'),
        ]);

        $client = static::createClient();

        $client->request('POST', '/election_criterias', [
            'json' => [
                'criteria' => '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0',
                'election' => '/elections/01EVXT7ZW43FKMTX52VB03R4MY',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        ElectionCriteriaFactory::repository()->assertExists([
            'election' => $election,
            'criteria' => $criteria,
        ]);
    }

    /**
     * @group functional
     */
    public function testDeleteElectionCriteria(): void
    {
        ElectionCriteriaFactory::new()->create([
            'ulid' => Ulid::fromString('01EVXT7ZW43FKMTX52VB03R4MY'),
        ]);

        ElectionCriteriaFactory::repository()->assertExists([
            'ulid' => '01EVXT7ZW43FKMTX52VB03R4MY',
        ]);

        $client = static::createClient();

        $client->request('DELETE', '/election_criterias/01EVXT7ZW43FKMTX52VB03R4MY', [
            'json' => [],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
