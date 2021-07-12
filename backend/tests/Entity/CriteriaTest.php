<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use App\Factory\CriteriaFactory;
use App\Factory\UserFactory;
use App\Tests\CustomApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Proxy;

class CriteriaTest extends CustomApiTestCase
{
    /**
     * @group functional
     */
    public function testUnauthenticatedUserCannotCreateCriteria(): void
    {
        $client = static::createClient();

        $client->request('POST', '/criterias', [
            'json' => [
                'name' => 'Region',
                'pictogram' => 'region',
                'items' => [
                    [
                        'name' => 'Vaud',
                        'acronym' => 'VD',
                    ], [
                        'name' => 'Valais',
                        'acronym' => 'VS',
                    ], [
                        'name' => 'Fribourg',
                        'acronym' => 'FR',
                    ],
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group functional
     */
    public function testCreateCriteriaForAuthenticatedUserSuccessfully(): void
    {
        UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        $client = self::createAuthenticatedClient('john.doe@gmail.com');

        $client->request('POST', '/criterias', [
            'json' => [
                'name' => 'Region',
                'pictogram' => 'region',
                'items' => [
                    [
                        'name' => 'Vaud',
                        'acronym' => 'VD',
                    ], [
                        'name' => 'Valais',
                        'acronym' => 'VS',
                    ], [
                        'name' => 'Fribourg',
                        'acronym' => 'FR',
                    ],
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        CriteriaFactory::repository()->assertExists([
            'name' => 'Region',
        ]);
    }

    /**
     * @group functional
     * @dataProvider criteriaUnsuccessfulProvider
     */
    public function testCreateCriteriaUnsuccessfully(?string $name, ?string $pictogram, array $items, int $code): void
    {
        UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        $client = static::createAuthenticatedClient('john.doe@gmail.com');

        $client->request('POST', '/criterias', [
            'json' => [
                'name' => $name,
                'pictogram' => $pictogram,
                'items' => $items,
            ],
        ]);

        self::assertResponseStatusCodeSame($code);
    }

    /**
     * @group functional
     */
    public function testCanNotShowOwnCriteriaIfNotAuthenticated(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        CriteriaFactory::new()->create([
            'ulid' => '01EVXSWVFEW54FJA7H15ZKN0R0',
            'user' => $user,
        ]);

        $client = static::createClient();

        $client->request('GET', '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group functional
     */
    public function testCanShowOwnCriteria(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        CriteriaFactory::new()->create([
            'ulid' => '01EVXSWVFEW54FJA7H15ZKN0R0',
            'user' => $user,
        ]);

        $client = static::createAuthenticatedClient('john.doe@gmail.com');

        $client->request('GET', '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @group functional
     */
    public function testCanNotShowOtherUserCriteria(): void
    {
        $user1 = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        CriteriaFactory::new()->create([
            'ulid' => '01EVXSWVFEW54FJA7H15ZKN0R0',
            'user' => $user1,
        ]);

        UserFactory::new()->create([
            'email' => 'jane.doe@gmail.com',
        ]);

        $client = static::createAuthenticatedClient('jane.doe@gmail.com');

        $client->request('GET', '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0');

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @group functional
     */
    public function testCanEditOwnCriteria(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        /** @var Proxy $criteria */
        $criteria = CriteriaFactory::new()->create([
            'ulid' => '01EVXSWVFEW54FJA7H15ZKN0R0',
            'name' => 'GenDDer', // typo in name
            'user' => $user,
        ]);

        CriteriaFactory::repository()->assertExists([
            'name' => 'GenDDer',
        ]);

        $client = static::createAuthenticatedClient('john.doe@gmail.com');

        $client->request('PUT', '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0', [
            'json' => [
                'name' => 'Gender',  // typo correction
                'items' => [
                    [
                        'name' => 'Woman',
                        'acronym' => 'Woman',
                    ],
                ],
            ],
        ]);

        $criteria->refresh();
        $this->assertSame('Gender', $criteria->getName()); // @phpstan-ignore-line
        $this->assertNotSame('GenDDer', $criteria->getName()); // @phpstan-ignore-line

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @group functional
     */
    public function testCanNotEditOtherUserCriteria(): void
    {
        $user1 = UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        CriteriaFactory::new()->create([
            'ulid' => '01EVXSWVFEW54FJA7H15ZKN0R0',
            'user' => $user1,
        ]);

        UserFactory::new()->create([
            'email' => 'jane.doe@gmail.com',
        ]);

        $client = static::createAuthenticatedClient('jane.doe@gmail.com');

        $client->request('PUT', '/criterias/01EVXSWVFEW54FJA7H15ZKN0R0', [
            'json' => [
                'name' => 'New criteria name',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function criteriaUnsuccessfulProvider(): \Generator
    {
        yield [null, 'region', [['name' => 'Vaud', 'acronym' => 'VD']], Response::HTTP_BAD_REQUEST];
        yield ['Region', null, [['name' => 'Vaud', 'acronym' => 'VD']], Response::HTTP_BAD_REQUEST];
        yield ['Region', 'region', [['name' => null, 'acronym' => 'VD']], Response::HTTP_BAD_REQUEST];
        yield ['Region', 'region', [['name' => 'Vaud', 'acronym' => null]], Response::HTTP_BAD_REQUEST];
        yield ['Region', 'region', [['name' => 'Vaud', 'acronym' => 'Too long']], Response::HTTP_UNPROCESSABLE_ENTITY];
        yield ['Region', 'region', [], Response::HTTP_UNPROCESSABLE_ENTITY];
    }
}
