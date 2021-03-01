<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Excel;

use App\Entity\Election;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface ExcelFactoryInterface
{
    public function create(Election $election): Worksheet;
}
