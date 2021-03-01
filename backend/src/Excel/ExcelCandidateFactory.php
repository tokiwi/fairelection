<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Excel;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Election;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExcelCandidateFactory implements ExcelFactoryInterface
{
    private const ELECTION_REFERENCE_ROW = 3;
    private const FIRST_HEADER_ROW = 4;
    private const LAST_HEADER_ROW = 6;
    private const FIRST_CANDIDATE_ROW = 7;
    private const LAST_CANDIDATE_ROW = 200;

    private static array $styleCentered = [
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
    ];

    private static array $styleSheetTitle = [
        'font' => [
            'name' => 'Arial',
            'bold' => true,
            'color' => ['rgb' => '000000'],
            'size' => 18,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ],
    ];

    private static array $styleHeader = [
        'font' => [
            'name' => 'Arial',
            'bold' => true,
            'color' => ['rgb' => 'ffffff'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => '00b48b'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];

    private static array $styleRowEven = [
        'font' => [
            'color' => ['rgb' => '000000'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => 'eeeeee'],
        ],
    ];

    private static array $styleCell = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];

    private static array $styleBorderTopMedium = [
        'borders' => [
            'top' => [
                'borderStyle' => Border::BORDER_MEDIUM,
            ],
        ],
    ];

    private static array $styleBorderBottomMedium = [
        'borders' => [
            'bottom' => [
                'borderStyle' => Border::BORDER_MEDIUM,
            ],
        ],
    ];

    private static array $styleBorderLeftMedium = [
        'borders' => [
            'left' => [
                'borderStyle' => Border::BORDER_MEDIUM,
            ],
        ],
    ];

    private static array $styleBorderRightMedium = [
        'borders' => [
            'right' => [
                'borderStyle' => Border::BORDER_MEDIUM,
            ],
        ],
    ];

    private TranslatorInterface $translator;
    private IriConverterInterface $iriConverter;

    public function __construct(TranslatorInterface $translator, IriConverterInterface $iriConverter)
    {
        $this->translator = $translator;
        $this->iriConverter = $iriConverter;
    }

    public function create(Election $election): Worksheet
    {
        $spreadsheet = (new Spreadsheet())
            ->setActiveSheetIndex(0);

        // default row height
        $spreadsheet->getDefaultRowDimension()
            ->setRowHeight(20)
        ;

        // candidate cell
        $spreadsheet->setCellValue('A'.self::FIRST_HEADER_ROW, $this->translator->trans('word.name_or_candidate_number'))
            ->mergeCells('A'.self::FIRST_HEADER_ROW.':A'.self::LAST_HEADER_ROW)
            ->getColumnDimension('A')
            ->setWidth(40)
        ;

        // votes cell
//        $spreadsheet->setCellValue('B'.self::FIRST_HEADER_ROW, $this->translator->trans('word.votes'))
//            ->mergeCells('B'.self::FIRST_HEADER_ROW.':B'.self::LAST_HEADER_ROW)
//            ->getColumnDimension('B')
//            ->setWidth(10)
//        ;

        $column = 'B';
        $column2 = 'B';
        $row = 4;
        $criteriaColumnStarts = [];
        $columnMergeEnd = 'B';

        foreach ($election->getElectionCriterias() as $electionCriteria) {
            if (null === $criteria = $electionCriteria->getCriteria()) {
                continue;
            }

            $columnMergeStart = $column;
            $criteriaColumnStarts[] = $columnMergeStart;

            $spreadsheet->setCellValue($column.$row, $criteria->getName());

            foreach ($criteria->getItems() as $key => $item) {
                $spreadsheet->setCellValue($column.($row + 2), $item->getAcronym());

                // check first candidate row as example
                if (0 === $key) {
                    $spreadsheet->setCellValue($column.($row + 3), 'x');
                }

                $columnMergeEnd = $column++;
            }

            foreach ($electionCriteria->getAssignments() as $assignment) {
                $spreadsheet->setCellValue($column2++.($row + 1), $this->iriConverter->getIriFromItem($assignment));
            }

            $spreadsheet->mergeCells($columnMergeStart.$row.':'.$columnMergeEnd.$row);
        }

        // sheet title
        $spreadsheet
            ->setCellValue('A1', $election->getName())
            ->mergeCells('A1:'.$columnMergeEnd.'2')
            ->getStyle('A1:'.$columnMergeEnd.'2')
            ->applyFromArray(self::$styleSheetTitle)
        ;

        // sheet title
        $spreadsheet->setCellValue('A3', $election->getUlid()->toBase32());
        $spreadsheet->getRowDimension(self::ELECTION_REFERENCE_ROW)->setVisible(false);

        // table header
        $spreadsheet->getStyle('A'.self::FIRST_HEADER_ROW.':'.$columnMergeEnd.self::LAST_HEADER_ROW)
            ->applyFromArray(self::$styleHeader)
            ->applyFromArray(self::$styleBorderTopMedium)
            ->applyFromArray(self::$styleBorderBottomMedium)
        ;

        // hide assignments iri row
        $spreadsheet->getRowDimension(self::FIRST_HEADER_ROW + 1)->setVisible(false);

        // center all table cells
        $spreadsheet->getStyle(sprintf('A'.self::FIRST_HEADER_ROW.':%s%d', $columnMergeEnd, self::LAST_CANDIDATE_ROW))
            ->applyFromArray(self::$styleCentered);

        // thin border to all candidate cells
        $spreadsheet->getStyle(sprintf('A%d:%s%d', self::FIRST_CANDIDATE_ROW, $columnMergeEnd, self::LAST_CANDIDATE_ROW))
            ->applyFromArray(self::$styleCell)
        ;

        // medium left border to all criteria cells
        foreach ($criteriaColumnStarts as $criteriaColumnStart) {
            $spreadsheet->getStyle(sprintf('%s%d:%s%d', $criteriaColumnStart, self::FIRST_HEADER_ROW, $criteriaColumnStart, self::LAST_CANDIDATE_ROW))
                ->applyFromArray(self::$styleBorderLeftMedium);
        }

        // table thick border
        $spreadsheet->getStyle(sprintf('A'.self::FIRST_HEADER_ROW.':%s%d', $columnMergeEnd, self::LAST_CANDIDATE_ROW))
            ->applyFromArray(self::$styleBorderLeftMedium)
            ->applyFromArray(self::$styleBorderRightMedium)
            ->applyFromArray(self::$styleBorderTopMedium)
            ->applyFromArray(self::$styleBorderBottomMedium)
        ;

        // candidate rows
        foreach (range(self::FIRST_CANDIDATE_ROW, self::LAST_CANDIDATE_ROW) as $row) {
            // allow only "X" character
            $spreadsheet->getDataValidation(sprintf('B%d:'.$columnMergeEnd.'%d', $row, $row))
                ->setType(DataValidation::TYPE_LIST)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setFormula1('"x, X"')
                ->setAllowBlank(true)
                ->setShowErrorMessage(true)
                ->setErrorTitle($this->translator->trans('word.error'))
                ->setError($this->translator->trans('message.only_x_allowed'))
            ;

            // color even row
            if (0 === $row % 2) {
                $spreadsheet->getStyle(sprintf('A%d:'.$columnMergeEnd.'%d', $row, $row))
                    ->applyFromArray(self::$styleRowEven)
                ;
            }
        }

        // add first candidate row
        $spreadsheet->setCellValue('A'.self::FIRST_CANDIDATE_ROW, $this->translator->trans('word.candidate_example'));

        return $spreadsheet;
    }
}
