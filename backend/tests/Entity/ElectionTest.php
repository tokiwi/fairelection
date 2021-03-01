<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ElectionFactory;
use Symfony\Component\HttpFoundation\Response;

class ElectionTest extends ApiTestCase
{
    /**
     * @group functional
     * @dataProvider electionProvider
     */
    public function testCreateElectionAnonymously(string $name, ?string $description): void
    {
        $client = static::createClient();

        $client->request('POST', '/elections', [
            'json' => [
                'name' => $name,
                'description' => $description,
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        ElectionFactory::repository()->assertExists([
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function electionProvider(): \Generator
    {
        yield ['Communales 2020 Martigny', 'On vote Première réussie pour les Verts qui font leur entrée au Conseil général de Martigny et décrochent huit sièges.'];
        yield ['Communales 2020 Martigny', null];
    }
}
