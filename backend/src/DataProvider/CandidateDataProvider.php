<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Candidate;
use App\Entity\User;
use App\Repository\CandidateRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Ulid;

class CandidateDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private CandidateRepository $candidateRepository;
    private Security $security;

    public function __construct(CandidateRepository $candidateRepository, Security $security)
    {
        $this->candidateRepository = $candidateRepository;
        $this->security = $security;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('You must be authenticated to access this resource.');
        }

        $ulid = isset($context['filters']['election']) ? Ulid::fromString($context['filters']['election']) : null;

        return $this->candidateRepository->findAllByOwner($user, $ulid);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Candidate::class === $resourceClass;
    }
}
