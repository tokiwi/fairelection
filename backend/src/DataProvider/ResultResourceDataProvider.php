<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Repository\ElectionRepository;
use App\Repository\ResultRepository;
use App\Resource\ResultItemResource;
use App\Resource\ResultResource;
use Symfony\Component\Uid\Ulid;

class ResultResourceDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private ResultRepository $resultRepository;
    private ElectionRepository $electionRepository;

    public function __construct(ResultRepository $resultRepository, ElectionRepository $electionRepository)
    {
        $this->resultRepository = $resultRepository;
        $this->electionRepository = $electionRepository;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $ulid = Ulid::fromString($id); // @phpstan-ignore-line

        $election = $this->electionRepository->findOneByUlid($ulid);
        $results = $this->resultRepository->findAllByElectionUlid($ulid);

        $stats = [];

        $resultResource = new ResultResource();
        $resultResource->election = $election;

        foreach ($results as $result) {
            $candidate = $result->getCandidate();
            if (null === $election = $result->getElection()) {
                throw new \LogicException('Election must not be null.');
            }

            $criterias = [];
            foreach ($election->getElectionCriterias() as $key => $electionCriteria) {
                if (null === $criteria = $electionCriteria->getCriteria()) {
                    throw new \LogicException('Criteria must not be null.');
                }

                $criterias[$key] = ['name' => $criteria->getName(), 'pictogram' => $criteria->getPictogram()];

                foreach ($criteria->getItems() as $item) {
                    $isElectedAndItemSelected = $result->getIsElected() && $candidate->hasAssignmentForItem($item) ? 1 : 0;

                    if (!isset($stats[$criteria->getPictogram()][$item->getName()])) {
                        $stats[$criteria->getPictogram()][$item->getName()] = $isElectedAndItemSelected;
                    } else {
                        $stats[$criteria->getPictogram()][$item->getName()] += $isElectedAndItemSelected;
                    }

                    $criterias[$key]['choices'][] = ['name' => $item->getName(), 'acronym' => $item->getAcronym(), 'isSelected' => $candidate->hasAssignmentForItem($item)];
                }
            }

            $resultItemResource = new ResultItemResource();
            $resultItemResource->votes = $candidate->getNumberOfVotes();
            $resultItemResource->isElected = $result->getIsElected();
            $resultItemResource->candidate = $candidate->getName();
            $resultItemResource->criterias = $criterias;

            $resultResource->rows[] = $resultItemResource;
        }

        $resultResource->stats = $stats;

        return $resultResource;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ResultResource::class === $resourceClass;
    }
}
