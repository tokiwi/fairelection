<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataPersister;

use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Assignment;
use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use App\Resource\CandidateResource;
use Doctrine\ORM\EntityManagerInterface;

class CandidateResourceDataPersister implements ContextAwareDataPersisterInterface
{
    private IriConverterInterface $iriConverter;
    private EntityManagerInterface $entityManager;
    private CandidateRepository $candidateRepository;

    public function __construct(IriConverterInterface $iriConverter, EntityManagerInterface $entityManager, CandidateRepository $candidateRepository)
    {
        $this->iriConverter = $iriConverter;
        $this->entityManager = $entityManager;
        $this->candidateRepository = $candidateRepository;
    }

    /**
     * @param CandidateResource $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof CandidateResource && 'post' === ($context['collection_operation_name'] ?? null);
    }

    /**
     * @param CandidateResource $data
     */
    public function persist($data, array $context = []): void
    {
        foreach ($data->candidates as $candidateData) {
            /** @var Candidate $candidate */
            $candidate = null !== $candidateData['@id'] ?
                $this->iriConverter->getItemFromIri($candidateData['@id']) :
                new Candidate()
            ;

            $candidate->setName($candidateData['name']);
            $candidate->removeAssignments();

            foreach ($candidateData['electionCriterias'] as $electionCriteria) {
                /** @var Assignment $assignment */
                $assignment = $this->iriConverter->getItemFromIri($electionCriteria['selectedValue']);
                $candidate->addAssignment($assignment);

                if (null === $electionCriteria = $assignment->getElectionCriteria()) {
                    throw new \LogicException('Election criteria must not be null');
                }

                $candidate->setElection($electionCriteria->getElection());
            }

            $this->entityManager->persist($candidate);
        }

        // remove existing candidates not resubmitted
        $this->candidateRepository->removeAllNotInIri($data->election, array_map(static fn ($c) => $c['@id'], $data->candidates));

        $this->entityManager->flush();
    }

    /**
     * @param CandidateResource $data
     */
    public function remove($data, array $context = []): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
