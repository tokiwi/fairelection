<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Response;

class CustomApiTestCase extends ApiTestCase
{
    /**
     * Authenticate a given user, and use the authentication token to
     * authenticate subsequent requests.
     */
    public static function createAuthenticatedClient(string $email, string $password = 'password'): Client
    {
        $client = static::createClient();
        $response = $client->request('POST', '/authenticate', [
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK, 'Unable to authenticate.');

        $payload = $response->toArray();

        self::assertArrayHasKey('token', $payload, 'The authenticate endpoint does\'t return a token.');

        // authenticate the client for next requests
        $client->setDefaultOptions([
            'auth_bearer' => $payload['token'],
        ]);

        return $client;
    }
}
