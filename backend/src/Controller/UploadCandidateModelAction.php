<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Election;
use App\Excel\ExcelCandidateImporter;
use App\Repository\ElectionRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Ulid;

final class UploadCandidateModelAction
{
    private ExcelCandidateImporter $importer;
    private ElectionRepository $electionRepository;

    public function __construct(ExcelCandidateImporter $importer, ElectionRepository $electionRepository)
    {
        $this->importer = $importer;
        $this->electionRepository = $electionRepository;
    }

    public function __invoke(Request $request): Election
    {
        /** @var UploadedFile|null $uploadedFile */
        $uploadedFile = $request->files->get('file');
        if (null === $uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $election = $this->electionRepository->findOneByUlid(Ulid::fromString($request->query->get('election', '')));
        $this->importer->import($uploadedFile, $election);

        return $election;
    }
}
