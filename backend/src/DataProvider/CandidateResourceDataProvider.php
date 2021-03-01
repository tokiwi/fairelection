<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataProvider;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use App\Resource\CandidateResource;
use Symfony\Component\Uid\Ulid;

class CandidateResourceDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private CandidateRepository $candidateRepository;
    private IriConverterInterface $iriConverter;

    public function __construct(CandidateRepository $candidateRepository, IriConverterInterface $iriConverter)
    {
        $this->candidateRepository = $candidateRepository;
        $this->iriConverter = $iriConverter;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $candidateResource = new CandidateResource();
        $candidates = $this->candidateRepository->findAllByElection(Ulid::fromString($id)); // @phpstan-ignore-line

        /** @var Candidate $candidate */
        foreach ($candidates as $candidate) {
            $data = [];
            $data['@id'] = $this->iriConverter->getIriFromItem($candidate);
            $data['name'] = $candidate->getName();

            foreach ($candidate->getAssignments() as $assignment) {
                $data['electionCriterias'][] = ['selectedValue' => $this->iriConverter->getIriFromItem($assignment)];
            }

            $candidateResource->candidates[] = $data;
        }

        return $candidateResource;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return CandidateResource::class === $resourceClass;
    }
}
