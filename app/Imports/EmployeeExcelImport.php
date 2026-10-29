<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Class EmployeeExcelImport
 * Handles the imported employee data from excel functionality.
 *
 * @author Hau Sian Cing
 * @created 26/06/2023
 */
class EmployeeExcelImport implements WithMultipleSheets
{
    /** @var FirstSheetImport */
    private $firstSheet;

    public function __construct()
    {
        $this->firstSheet = new FirstSheetImport();
    }

    /**
     * get a sheet for employee data
     * @author Hau Sian Cing
     * @created 26/06/2023
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this->firstSheet,
        ];
    }

    /**
     * Error messages collected while importing (empty when the import succeeded)
     * @return array
     */
    public function errors(): array
    {
        return $this->firstSheet->errors;
    }

    /**
     * Number of employees created
     * @return int
     */
    public function importedCount(): int
    {
        return $this->firstSheet->importedCount;
    }
}

/**
 * Class FirstSheetImport
 * Validates every row first; saves nothing unless all rows are valid.
 *
 * @author Hau Sian Cing
 * @created 26/06/2023
 */
class FirstSheetImport implements ToCollection
{
    const MAX_ROWS = 100;

    /** @var array list of error messages */
    public $errors = [];

    /** @var int */
    public $importedCount = 0;

    /**
     * Column names used in the validation messages
     * @return array
     */
    public function attributeNames()
    {
        return [
            '0' => 'Employee Code',
            '1' => 'Employee Name',
            '2' => 'NRC Number',
            '3' => 'Password',
            '4' => 'Email Address',
            '5' => 'Gender',
            '6' => 'Date of Birth',
            '7' => 'Marital Status',
            '8' => 'Address',
        ];
    }

    /**
     * Validate and import the employee rows
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        // Remove the heading row and fully empty rows
        $dataRows = $rows->slice(1)->filter(function ($row) {
            return $row->filter(function ($cell) {
                return $cell !== null && $cell !== '';
            })->isNotEmpty();
        });

        if ($dataRows->isEmpty()) {
            $this->errors[] = 'Your File has no Data';
            return;
        }
        if ($dataRows->count() > self::MAX_ROWS) {
            $this->errors[] = 'Your imported data cannot be over ' . self::MAX_ROWS . ' rows';
            return;
        }

        $rules = [
            '0' => 'required|max:25',
            '1' => 'required|max:50',
            '2' => 'required|regex:/^[a-zA-Z0-9\/\(\)]+$/',
            '3' => 'required|min:4|max:8',
            '4' => 'required|email|unique:employees,email_address|max:50',
            '5' => 'nullable|in:1,2',
            '6' => 'required',
            '7' => 'nullable|in:1,2,3',
            '8' => 'nullable|max:500',
        ];

        $prepared = [];
        $emailsInFile = [];

        // 1) Validate every row and collect all errors
        foreach ($dataRows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 1; // row number as shown in Excel
            $validator = Validator::make($row->toArray(), $rules, [], $this->attributeNames());
            foreach ($validator->errors()->all() as $message) {
                $this->errors[] = $message . ' in row ' . $rowNumber;
            }

            $email = strtolower(trim((string) $row[4]));
            if ($email !== '' && in_array($email, $emailsInFile, true)) {
                $this->errors[] = 'Email address is duplicated in row ' . $rowNumber;
            }
            $emailsInFile[] = $email;

            $birthDate = $row[6];
            if (!is_numeric($birthDate)) {
                $this->errors[] = 'Birth date must be a valid date in row ' . $rowNumber;
                continue;
            }
            $date = Date::excelToDateTimeObject($birthDate);
            if ($date >= new \DateTime('today')) {
                $this->errors[] = 'Birth date must be before today in row ' . $rowNumber;
                continue;
            }

            $prepared[] = [$row, $date->format('Y-m-d')];
        }

        if (!empty($this->errors)) {
            return;
        }

        // 2) Save everything in one transaction: all rows or nothing
        DB::transaction(function () use ($prepared) {
            $employeeId = (new EmployeeRepository())->getNewEmployeeID();
            foreach ($prepared as [$row, $birthDate]) {
                $employee = new Employee();
                $employee->employee_id = $employeeId++;
                $employee->employee_code = $row[0];
                $employee->employee_name = $row[1];
                $employee->nrc_number = $row[2];
                $employee->password = Hash::make($row[3]);
                $employee->email_address = $row[4];
                $employee->gender = $row[5];
                $employee->date_of_birth = $birthDate;
                $employee->marital_status = $row[7];
                $employee->address = $row[8];
                $employee->save();
                $this->importedCount++;
            }
        });
    }
}
