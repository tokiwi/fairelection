<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Candidate;
use Doctrine\ORM\EntityManagerInterface;

class CandidateDataPersister implements ContextAwareDataPersisterInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     */
    public function supports($data, array $context = []): bool
    {
        return is_iterable($data) && 'candidate_collection_votes' === ($context['collection_operation_name'] ?? null);
    }

    /**
     * @param array $data
     */
    public function persist($data, array $context = []): void
    {
        array_map(function (Candidate $candidate) {
            $this->entityManager->persist($candidate);
        }, $data);

        $this->entityManager->flush();
    }

    /**
     * @param array $data
     */
    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
