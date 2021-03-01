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
use App\Entity\Criteria;
use App\Entity\User;
use App\Repository\CriteriaRepository;
use App\Repository\ElectionRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Ulid;

class CriteriaDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private ElectionRepository $electionRepository;
    private CriteriaRepository $criteriaRepository;
    private Security $security;

    public function __construct(ElectionRepository $electionRepository, CriteriaRepository $criteriaRepository, Security $security)
    {
        $this->electionRepository = $electionRepository;
        $this->criteriaRepository = $criteriaRepository;
        $this->security = $security;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $ulid = isset($context['filters']['election']) ? Ulid::fromString($context['filters']['election']) : null;
        if (null === $ulid) {
            throw new \LogicException('Query string election is mandatory');
        }

        $election = $this->electionRepository->findOneByUlid($ulid);

        /** @var User $user */
        $user = $this->security->getUser();

        return $this->criteriaRepository->findAllByElectionAndUser($election, $user);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Criteria::class === $resourceClass;
    }
}
