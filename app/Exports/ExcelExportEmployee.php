<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Class ExcelExportEmployee
 * Handles excel file functionality.
 *
 * @author Hau Sian Cing
 * @created 23/6/2023
 */
class ExcelExportEmployee implements WithMultipleSheets
{
    
    /**
     *add 3 sheets in one excel file
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function sheets(): array
    {
        $sheet = [
            new EmployeeRegistrationSheet,
            new GenderSheet,
            new MaritalStatusSheet,
        ];
        return $sheet;
    }
}
/**
 * Class EmployeeRegistrationSheet
 * Handles excel file functionality.
 *
 * @author Hau Sian Cing
 * @created 23/6/2023
 */
class EmployeeRegistrationSheet implements WithColumnWidths, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    /**
     *styling the employee registration sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function styles(Worksheet $sheet)
    {


        $styleArrayRed = [
            'font' => [
                'color' => ['rgb' => 'FF0000'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4c3d40'
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $styleArrayWhite = [
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4c3d40'
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($styleArrayRed);
        $sheet->getStyle('G1')->applyFromArray($styleArrayRed);
        $sheet->getStyle('F1')->applyFromArray($styleArrayWhite);
        $sheet->getStyle('H1:I1')->applyFromArray($styleArrayWhite);
        $sheet->getRowDimension(1)->setRowHeight(30);
        return [];
    }

    /**
     *adding headers in employee registration sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function headings(): array
    {

        $header = [

            "Employee Code",
            "Employee Name",
            "NRC Number",
            "Password",
            "Email Address",
            "Gender",
            "Date of Birth",
            "Marital Status",
            "Address"

        ];
        return $header;
    }

    /**
     *adding title for employee registration sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return string
     */
    public function title(): string
    {

        return 'Employee Registration';
    }

    /**
     *align the column width in employee registration sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function columnWidths(): array
    {

        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            'D' => 15,
            'E' => 25,
            'F' => 13,
            'G' => 25,
            'H' => 25,
            'I' => 50

        ];
    }
}
/**
 * Class MaritalStatusSheet
 * Handles excel file functionality.
 *
 * @author Hau Sian Cing
 * @created 23/6/2023
 */
class MaritalStatusSheet implements WithColumnWidths, WithHeadings, WithTitle, FromCollection, WithStyles, ShouldAutoSize
{
    /**
     * styling the marital status sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function styles(Worksheet $sheet)
    {

        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '4c3d40'],
                ],
            ],
        ];

        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray($styleArray)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);


        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                    'size' => 14,

                ]

            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 14,

                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '4c3d40'
                    ]
                ]
            ]
        ];
    }
    /**
     * adding headers in marital status sheet 
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function headings(): array
    {

        $header = [
            ["Marital Status"],
            [
                "ID",
                "Marital Status"
            ]
        ];
        return $header;
    }

    /**
     * adding value in excel column in marital status sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return object
     */
    public function collection()
    {

        return new Collection([
            [
                "ID" => 1,
                "Marital Status" => "Single"
            ],
            [
                "ID" => 2,
                "Marital Status" => "Married"
            ],
            [
                "ID" => 3,
                "Marital Status" => "Divorce"
            ]
        ]);
    }

    /**
     * adding title for Marital Status sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return string
     */
    public function title(): string
    {
        return 'Marital Status List';
    }

    /**
     * align the column width in marital status sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function columnWidths(): array
    {

        return [
            'A' => 25,
            'B' => 25,

        ];
    }
}

/**
 * Class GenderSheet
 * Handles excel file functionality.
 *
 * @author Hau Sian Cing
 * @created 23/6/2023
 */
class GenderSheet implements WithColumnWidths, WithHeadings, WithTitle, FromCollection, ShouldAutoSize, WithStyles
{

    /**
         *styling the gender sheet
         *@author Hau Sian Cing
         * @created 23/6/2023
         * @return array
         */
    public function styles(Worksheet $sheet)
    {
        

        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '4c3d40'],
                ],
            ],
        ];

        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray($styleArray)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        return [
            1 => [

                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                    'size' => 14,


                ]
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 14,

                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '4c3d40'
                    ]
                ]
            ]
        ];
    }
    /**
     *adding headers in gender sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function headings(): array
    {


        $header = [
            ["Gender List"],
            [
                "ID",
                "Gender Name",
            ]
        ];
        return $header;
    }
    /**
     *adding headers in gender sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return array
     */
    public function columnWidths(): array
    {

        return [
            'A' => 15,
            'B' => 25,

        ];
    }
    /**
     *adding value in excel column in gender sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return object
     */

    public function collection()
    {

        return new Collection([
            [
                "ID" => 1,
                "Gender Name" => "Male"
            ],
            [
                "ID" => 2,
                "Gender Name" => "Female"
            ]
        ]);
    }
    /**
     *adding title for gender sheet
     *@author Hau Sian Cing
     * @created 23/6/2023
     * @return string
     */
    public function title(): string
    {


        return 'Gender List';
    }
}
