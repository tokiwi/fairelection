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

class ExcelVoteFactory implements ExcelFactoryInterface
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
            ->setWidth(30)
        ;

        // votes cell
        $spreadsheet->setCellValue('B'.self::FIRST_HEADER_ROW, $this->translator->trans('word.votes'))
            ->mergeCells('B'.self::FIRST_HEADER_ROW.':B'.self::LAST_HEADER_ROW)
            ->getColumnDimension('B')
            ->setWidth(10)
        ;

        $criteriaColumnStarts = [];

        // sheet title
        $spreadsheet
            ->setCellValue('A1', $election->getName())
            ->mergeCells('A1:B2')
            ->getStyle('A1:B2')
            ->applyFromArray(self::$styleSheetTitle)
        ;

        // sheet title
        $spreadsheet->setCellValue('A3', $election->getUlid()->toBase32());
        $spreadsheet->getRowDimension(self::ELECTION_REFERENCE_ROW)->setVisible(false);

        // table header
        $spreadsheet->getStyle('A'.self::FIRST_HEADER_ROW.':B'.self::LAST_HEADER_ROW)
            ->applyFromArray(self::$styleHeader)
            ->applyFromArray(self::$styleBorderTopMedium)
            ->applyFromArray(self::$styleBorderBottomMedium)
        ;

        // hide assignments iri row
        $spreadsheet->getRowDimension(self::FIRST_HEADER_ROW + 1)->setVisible(false);

        // center all table cells
        $spreadsheet->getStyle(sprintf('A'.self::FIRST_HEADER_ROW.':B%d', self::LAST_CANDIDATE_ROW))
            ->applyFromArray(self::$styleCentered);

        $totalCandidates = $election->getCandidates()->count();
        $lastTableRow = $totalCandidates + self::FIRST_CANDIDATE_ROW - 1;

        // thin border to all candidate cells
        $spreadsheet->getStyle(sprintf('A%d:B%d', self::FIRST_CANDIDATE_ROW, $lastTableRow))
            ->applyFromArray(self::$styleCell)
        ;

        // table thick border
        $spreadsheet->getStyle(sprintf('A'.self::FIRST_HEADER_ROW.':B%d', $lastTableRow))
            ->applyFromArray(self::$styleBorderLeftMedium)
            ->applyFromArray(self::$styleBorderRightMedium)
            ->applyFromArray(self::$styleBorderTopMedium)
            ->applyFromArray(self::$styleBorderBottomMedium)
        ;

        // candidate rows
        $row = self::FIRST_CANDIDATE_ROW;
        foreach ($election->getCandidates() as $candidate) {
            $spreadsheet->setCellValue(sprintf('A%d', $row), $candidate->getName());
            $spreadsheet->setCellValue(sprintf('B%d', $row), $candidate->getNumberOfVotes());
            $spreadsheet->setCellValue(sprintf('C%d', $row), $this->iriConverter->getIriFromItem($candidate));

            // allow only numbers
            $spreadsheet->getDataValidation(sprintf('B%d', $row))
                ->setType(DataValidation::TYPE_WHOLE)
                ->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL)
                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                ->setFormula1('0')
                ->setAllowBlank(true)
                ->setShowErrorMessage(true)
                ->setErrorTitle('Input error')
                ->setError('Only numbers greater or equal than zero are allowed.')
            ;

            // color even row
            if (0 === $row % 2) {
                $spreadsheet->getStyle(sprintf('A%d:B%d', $row, $row))
                    ->applyFromArray(self::$styleRowEven)
                ;
            }

            ++$row;
        }

        // hide candidate iri column
        $spreadsheet->getColumnDimension('C')->setVisible(false);

        return $spreadsheet;
    }
}
