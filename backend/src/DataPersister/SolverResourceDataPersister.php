<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Result;
use App\FairElection\Solver\SolverInputFactory;
use App\FairElection\SolverInterface;
use App\Repository\ElectionRepository;
use App\Repository\ResultRepository;
use App\Resource\SolverResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;

class SolverResourceDataPersister implements ContextAwareDataPersisterInterface
{
    private SolverInterface $solver;
    private EntityManagerInterface $entityManager;
    private ?Request $request;

    private ElectionRepository $electionRepository;
    private ResultRepository $resultRepository;

    public function __construct(SolverInterface $solver, EntityManagerInterface $entityManager, RequestStack $requestStack, ElectionRepository $electionRepository, ResultRepository $resultRepository)
    {
        $this->entityManager = $entityManager;
        $this->solver = $solver;
        $this->request = $requestStack->getMasterRequest();
        $this->electionRepository = $electionRepository;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @param SolverResource $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof SolverResource && 'post' === ($context['collection_operation_name'] ?? null);
    }

    /**
     * @param SolverResource $data
     */
    public function persist($data, array $context = []): void
    {
        if (null === $this->request || (null === $ulid = $this->request->query->get('election') ? Ulid::fromString($this->request->query->get('election', '')) : null)) {
            throw new \LogicException('Passing an election query string with election ulid is mandatory.');
        }

        $election = $this->electionRepository->findOneByUlid($ulid);

        $input = SolverInputFactory::createFromElection($election);
        $result = $this->solver->solve($input);

        if (null !== $this->request->query->get('persist')) {
            return;
        }

        $connection = $this->entityManager->getConnection();
        $connection->beginTransaction();

        try {
            $this->resultRepository->deleteAllByElection($election);

            foreach ($input->objectsFromResult($result) as $object) {
                $result = new Result();
                $result->setElection($election);
                $result->setCandidate($object['object']);
                $result->setIsElected($object['is_elected']);

                $this->entityManager->persist($result);
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();

            throw $e;
        }

        $this->entityManager->flush();
    }

    /**
     * @param SolverResource $data
     */
    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
