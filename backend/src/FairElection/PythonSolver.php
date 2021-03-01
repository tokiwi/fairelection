<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\FairElection;

use App\FairElection\Solver\SolverInput;
use App\Traits\AppLoggerTrait;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PythonSolver implements SolverInterface
{
    use AppLoggerTrait;

    private const DEFAULT_API_ENDPOINT = 'https://alj20ln2ua.execute-api.eu-west-1.amazonaws.com/default/votingSolver';

    private HttpClientInterface $httpClient;
    private string $endpoint;

    public function __construct(HttpClientInterface $httpClient = null, string $endpoint = null)
    {
        if (null === $httpClient && !class_exists(HttpClient::class)) {
            throw new \LogicException(sprintf('The "%s" class requires the "HttpClient" component. Try running "composer require symfony/http-client".', self::class));
        }

        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->endpoint = $endpoint ?? self::DEFAULT_API_ENDPOINT;
    }

    public function solve(SolverInput $input): array
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer([new ObjectNormalizer($classMetadataFactory)]);
        $data = $serializer->normalize($input, null, ['groups' => ['read']]);

        $this->logInfo('Solver call', [
            'input' => json_encode($data),
        ]);

        try {
            $result = $this->httpClient->request('POST', $this->endpoint, [
                'json' => $data,
            ]);

            if (Response::HTTP_OK !== $result->getStatusCode()) {
                throw new NoResultException('The fair election algorithm could not return results for these criteria.');
            }
        } catch (ExceptionInterface $e) {
            throw $e;
        }

        $this->logInfo('Solver result', [
            'output' => json_encode($result->toArray()),
        ]);

        return $result->toArray();
    }
}
