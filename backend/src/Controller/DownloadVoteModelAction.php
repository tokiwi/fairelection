<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Election;
use App\Excel\ExcelVoteFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class DownloadVoteModelAction
{
    public function __invoke(Election $data, ExcelVoteFactory $factory): Response
    {
        $worksheet = $factory->create($data);

        $filePath = sys_get_temp_dir().'/model.xlsx';
        $writer = IOFactory::createWriter($worksheet->getParent(), 'Xlsx');
        $writer->save($filePath);

        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'model.xlsx');

        return $response;
    }
}
