<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Excel;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Assignment;
use App\Entity\Candidate;
use App\Entity\Election;
use App\Exception\InvalidModelException;
use App\Repository\AssignmentRepository;
use App\Repository\CandidateRepository;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExcelCandidateImporter
{
    private const CANDIDATE_ROW_START = 6;

    private ResultRepository $resultRepository;
    private AssignmentRepository $assignmentRepository;
    private CandidateRepository $candidateRepository;
    private IriConverterInterface $iriConverter;
    private EntityManagerInterface $entityManager;

    public function __construct(ResultRepository $resultRepository, AssignmentRepository $assignmentRepository, CandidateRepository $candidateRepository, IriConverterInterface $iriConverter, EntityManagerInterface $entityManager)
    {
        $this->resultRepository = $resultRepository;
        $this->assignmentRepository = $assignmentRepository;
        $this->candidateRepository = $candidateRepository;
        $this->iriConverter = $iriConverter;
        $this->entityManager = $entityManager;
    }

    public function import(UploadedFile $file, Election $election): void
    {
        if (false === $tempFilePath = $file->getRealPath()) {
            throw new BadRequestHttpException('The file does\'t exists.');
        }

        $spreadsheet = IOFactory::load($tempFilePath);
        $data = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);
        $reference = $data['3']['A'];
        $assignments = $data['5'];

        if ($election->getUlid()->toBase32() !== $reference) {
            throw new InvalidModelException('The selected model does not belong to this election');
        }

        $candidates = array_values($data);
        $candidatesCount = \count($candidates);
        $latestColumn = array_key_last($assignments);

        $this->entityManager->transactional(function () use ($election) {
            $this->resultRepository->deleteAllByElection($election);
            $this->candidateRepository->deleteAllByElection($election);
            $this->assignmentRepository->deleteAllByElection($election);
        });

        for ($i = self::CANDIDATE_ROW_START; $i < $candidatesCount; ++$i) {
            if (null === $candidates[$i]['A']) {
                continue;
            }

            $row = $this->choicesToAssignment($assignments, $candidates[$i]);

            $candidate = new Candidate();
            $candidate->setName($candidates[$i]['A']);
            $candidate->setElection($election);

            foreach (range('B', $latestColumn) as $item) {
                if (null === $candidates[$i][$item]) {
                    continue;
                }

                /** @var Assignment $assignment */
                $assignment = $this->iriConverter->getItemFromIri($row[$item]);
                $candidate->addAssignment($assignment);
                $election->addCandidate($candidate);

                $this->entityManager->persist($candidate);
            }
        }

        $this->entityManager->flush();
    }

    private function choicesToAssignment(array $assignmentIris, array $row): array
    {
        $assignments = array_values($assignmentIris); // replace letters (A, B, ...) keys by numeric values
        $i = 0;

        return array_map(function ($value) use ($assignments, &$i) {
            $assignment = 'x' === $value || 'X' === $value ? $assignments[$i] : $value;

            ++$i;

            return $assignment;
        }, $row);
    }
}
