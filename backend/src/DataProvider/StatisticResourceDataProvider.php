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
use App\Repository\AssignmentRepository;
use App\Repository\CandidateRepository;
use App\Repository\ElectionRepository;
use App\Resource\StatisticItemResource;
use App\Resource\StatisticResource;
use App\Resource\StatisticRowResource;
use App\Utils\ArrayUtil;
use Symfony\Component\Uid\Ulid;

class StatisticResourceDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private ElectionRepository $electionRepository;
    private CandidateRepository $candidateRepository;
    private AssignmentRepository $assignmentRepository;

    public function __construct(ElectionRepository $electionRepository, CandidateRepository $candidateRepository, AssignmentRepository $assignmentRepository)
    {
        $this->electionRepository = $electionRepository;
        $this->candidateRepository = $candidateRepository;
        $this->assignmentRepository = $assignmentRepository;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $ulid = Ulid::fromString($id); // @phpstan-ignore-line

        $electedOnly = (bool) ($context['filters']['elected'] ?? false);

        $election = $this->electionRepository->findOneByUlid($ulid);
        $totalCandidate = $this->candidateRepository->countTotalCandidateByElection($ulid, $electedOnly);
        $statistics = $this->assignmentRepository->statisticsByElection($ulid, $electedOnly);

        $groupedByCriteria = ArrayUtil::groupBy('criteriaName', $statistics);

        $statisticResource = new StatisticResource();
        $statisticResource->election = $election;

        foreach ($groupedByCriteria as $statistic) {
            $statisticItemResource = new StatisticRowResource();
            $statisticItemResource->categoryName = $statistic[0]['criteriaName'];
            $statisticItemResource->categoryPictogram = $statistic[0]['criteriaPictogram'];

            foreach ($statistic as $item) {
                $item = new StatisticItemResource($item['candidateNumber'], $item['itemAcronym'], $item['target'], $totalCandidate);
                $statisticItemResource->addItem($item);
            }

            $statisticResource->rows[] = $statisticItemResource;
        }

        return $statisticResource;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return StatisticResource::class === $resourceClass;
    }
}
