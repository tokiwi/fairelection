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
use App\Entity\Election;
use App\Resource\AssignmentResource;
use Doctrine\ORM\EntityManagerInterface;

class AssignmentResourceDataPersister implements ContextAwareDataPersisterInterface
{
    private IriConverterInterface $iriConverter;
    private EntityManagerInterface $entityManager;

    public function __construct(IriConverterInterface $iriConverter, EntityManagerInterface $entityManager)
    {
        $this->iriConverter = $iriConverter;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AssignmentResource $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof AssignmentResource;
    }

    /**
     * @param AssignmentResource $data
     */
    public function persist($data, array $context = []): void
    {
        /** @var Election $election */
        $election = $this->iriConverter->getItemFromIri($data->election);
        $election->setNumberOfPeopleToElect($data->numberOfPeopleToElect);

        foreach ($election->getElectionCriterias() as $electionCriteria) {
            /* @var  $electionCriterion */
            foreach ($electionCriteria->getAssignments() as $assignment) {
                $assignmentIri = $this->iriConverter->getIriFromItem($assignment);
                if (null !== ($data->assignments[$assignmentIri] ?? null)) {
                    $assignment->setPercent($data->assignments[$assignmentIri]);
                }
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param AssignmentResource $data
     */
    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
