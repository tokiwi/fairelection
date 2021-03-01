<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Decorate Swagger documentation based on OpenAPI.
 *
 * OpenAPI Specification can be read here : https://swagger.io/docs/specification/about/
 */
class SwaggerDecorator implements NormalizerInterface
{
    private NormalizerInterface $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $docs = (array) $this->decorated->normalize($object, $format, $context);

        // login
        $loginDefinition = [
            'tags' => ['Security'],
            'security' => [],
            'summary' => 'Authenticate a user to the api',
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'required' => [
                                'email',
                                'password',
                            ],
                            'properties' => [
                                'email' => ['type' => 'string', 'example' => 'yves@pkw.ch'],
                                'password' => ['type' => 'string', 'example' => 'password'],
                            ],
                        ],
                    ],
                ],
                'name' => 'authenticate',
                'description' => 'The user credentials',
            ],
            'responses' => [
                200 => [
                    'description' => 'OK',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'token' => ['type' => 'string', 'example' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NzYyNDcyNzgsImV4cCI6MTU3NjI3NjA3OCwicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9TVVBFUl9BRE1JTiIsIlJPTEVfQURNSU4iLCJST0xFX0RJU1BBVENIRVIiLCJST0xFX1RFTEVQSE9OSVNUIl0sInVzZXJuYW1lIjoiamFuZV9zdXBlcl9hZG1pbkBuZXpyb3VnZS5jaCJ9.JLnmFWIWd4aBtfMnNX-cIT3QAvniZoNV8ZRA5jnSZhfqge6G2_wxinlfKr82OEX1gpJFTT7CEexpPowNUspTc2-IN4a8QNSD97uGEPCaw2jFTJTaMq0b57M5s0X5irK-qWf_zdNQAL3kSvlSsjKH2SMl-T6JPEGIK1QrevSUEKqL-og4jy82ANqBIhS4tdUNKjETLMhtjSL42R2Qq2mVCzC08XPn9TWkg2HVDKgceqCZAg9zqJSJ1CaJLtrji9kzz06TtRzHe4Z81MowyJsyfAoTSRr-taBLXax0_tvfXadI4kWSQtTe7-1U3yWHoAqSTgXtHeHZK853TRygUb3cLhW9GQPH8JQNHZzqtwnhg38aYbSaEnwUp69BnFLDY-A198WRGcXwAHW4FWJaHnHBZNtPQ9d6jJye6OcjHVbe8_6l38H4MQofxzi6vs3WSt7WNqzqfhtM3_LkR47gYsOGic0hmUU_LdJUNCdkvKfdfvzmFRIWtIyNYp9DL5u3XIV7iy_nPuVO6DSQ0xwdliQ5uRWFr-gCXdzyehXPGth0z0TkJ7D62Gn9Fk67dzTJ0G8PRYaSuHNX4dS4sf_gD8qTFELwq_nYFvyisCX9GC_FujaSSRdZZtuQx0W49UfOLnSC9jb8UkIxhzgbPttUBd1EegiqI7k9Pd3qs1W5IfP5lSs'],
                                ],
                            ],
                        ],
                    ],
                ],
                401 => [
                    'description' => 'Error: Unauthorized',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'properties' => [
                                    'code' => ['type' => 'number', 'example' => 401],
                                    'message' => ['type' => 'string', 'example' => 'Invalid credentials.'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $docs['paths']['/authenticate']['post'] = $loginDefinition;

        // remove security icon on public resources
        $docs['paths']['/register']['post']['tags'] = ['Security'];
        $docs['paths']['/register']['post']['security'] = [];

        return $docs;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
