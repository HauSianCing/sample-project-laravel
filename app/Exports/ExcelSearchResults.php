<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelSearchResults implements WithColumnWidths, WithHeadings, WithTitle, WithStyles, FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }
    public function collection()
    {
        $employees = $this->employees;
        $collection = new Collection();
        foreach ($employees as $result) {
            if ($result->gender == "1") {
                $gender = "Male";
            } elseif ($result->gender == "2") {
                $gender = "Female";
            }else {
                $gender = " - ";
            }
            if ($result->marital_status == "1") {
                $status = "Single";
            } elseif ($result->marital_status == "2") {
                $status = "Married";
            } elseif ($result->marital_status == "3") {
                $status = "Divorce";
            }else {
                $status = " - ";
            }
            if($result->address==null) {
                $address = ' - ';
            } else {
                $address = $result->address;
            }
            $collection->push([
                'employee_id' => $result->employee_id,
                'employee_code' => $result->employee_code,
                'employee_name' => $result->employee_name,
                'nrc_number' => $result->nrc_number,
                'email_address' => $result->email_address,
                'gender' => $gender,
                'date_of_birth' => $result->date_of_birth,
                'marital_status' => $status,
                'address' => $address,
            ]);
        }
            return $collection;
    }

    public function styles(Worksheet $sheet)
    {


        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        
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
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:H')->getAlignment()->setVertical('center');
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

            "Employee ID",
            "Employee Code",
            "Employee Name",
            "NRC Number",
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

        return 'Employee Search Results';
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
