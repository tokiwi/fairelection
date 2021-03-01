<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends ApiTestCase
{
    /**
     * @group functional
     */
    public function testUserRegisterSuccessfully(): void
    {
        $client = static::createClient();

        $client->request('POST', '/register', [
            'json' => [
                'email' => 'john.doe@gmail.com',
                'fullName' => 'John Doe',
                'password' => 'password',
            ],
        ]);

        self::assertResponseStatusCodeSame(201);

        UserFactory::repository()->assertExists([
            'email' => 'john.doe@gmail.com',
        ]);
    }

    /**
     * @group functional
     * @dataProvider invalidRegisterUserProvider
     */
    public function testUserRegisterUnsuccessfully(string $email, string $password, int $code): void
    {
        UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        static::ensureKernelShutdown();

        $client = static::createClient();

        $client->request('POST', '/register', [
            'json' => [
                'email' => $email,
                'fullName' => 'John Doe',
                'password' => $password,
            ],
        ]);

        self::assertResponseStatusCodeSame($code);
    }

    public function invalidRegisterUserProvider(): \Generator
    {
        yield ['john.doe@gmail.com', 'password', Response::HTTP_UNPROCESSABLE_ENTITY]; // email already exists
        yield ['john.doe', 'password', Response::HTTP_UNPROCESSABLE_ENTITY]; // invalid email
        yield ['john.doe@gmail.com', '', Response::HTTP_UNPROCESSABLE_ENTITY]; // empty password
        yield ['john.doe@gmail.com', 'pass', Response::HTTP_UNPROCESSABLE_ENTITY]; // too short
    }

    /**
     * @group functional
     */
    public function testAuthenticateSuccessfully(): void
    {
        UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        static::ensureKernelShutdown();

        $client = static::createClient();

        $response = $client->request('POST', '/authenticate', [
            'json' => [
                'email' => 'john.doe@gmail.com',
                'password' => 'password',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertArrayHasKey('token', $response->toArray());
    }

    /**
     * @group functional
     * @dataProvider invalidAuthenticateUserProvider
     */
    public function testAuthenticateUnsuccessfully(string $email, string $password): void
    {
        UserFactory::new()->create([
            'email' => 'john.doe@gmail.com',
        ]);

        static::ensureKernelShutdown();

        $client = static::createClient();

        $client->request('POST', '/authenticate', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function invalidAuthenticateUserProvider(): \Generator
    {
        yield ['john@gmail.com', 'password']; // wrong email
        yield ['john.doe@gmail.com', 'paSSword']; // wrong password
    }
}
