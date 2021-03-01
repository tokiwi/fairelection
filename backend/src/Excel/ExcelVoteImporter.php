<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Excel;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Candidate;
use App\Entity\Election;
use App\Exception\InvalidModelException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ExcelVoteImporter implements ExcelImporterInterface
{
    private const CANDIDATE_ROW_START = 6;

    private IriConverterInterface $iriConverter;

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    public function import(UploadedFile $file, Election $election): void
    {
        if (false === $tempFilePath = $file->getRealPath()) {
            throw new BadRequestHttpException('The file does\'t exists.');
        }

        $spreadsheet = IOFactory::load($tempFilePath);
        $data = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);

        $reference = $data['3']['A'];
        if ($election->getUlid()->toBase32() !== $reference) {
            throw new InvalidModelException('The selected model does not belong to this election');
        }

        $candidates = array_values($data);
        $candidatesCount = \count($candidates);

        for ($i = self::CANDIDATE_ROW_START; $i < $candidatesCount; ++$i) {
            if (null === $candidates[$i]['A']) {
                continue;
            }

            /** @var Candidate $candidate */
            $candidate = $this->iriConverter->getItemFromIri($candidates[$i]['C']);
            $candidate->setNumberOfVotes($candidates[$i]['B'] ?? 0);
        }
    }
}
