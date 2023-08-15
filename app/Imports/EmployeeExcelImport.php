<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
    /**
     * get a sheet for employee data
     *@author Hau Sian Cing
     * @created 26/06/2023
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => new FirstSheetImport()
        ];
    }
}
/**
 * Class FirstSheetImport
 * Handles the imported employee data from excel functionality.
 *
 * @author Hau Sian Cing
 * @created 26/06/2023
 */
class FirstSheetImport implements ToCollection
{
    /**
     * set attribute names for employee data
     *@author Hau Sian Cing
     * @created 26/06/2023
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
            '8' => 'Address'

        ];
    }

    /**
     * store employees' data via collection 
     *@author Hau Sian Cing
     * @created 26/06/2023
     * @return object
     */
    public function collection(Collection $rows)
    {
        $dataRows = $rows->slice(1); // to remove the heading in imported excel
        $rowCount = count($dataRows); //counting rows from the imported excel file
        $errors = [];
        if ($rowCount == 0) { // if there is no data in imported file, return error message
            $errors[] = [
                'error' =>  'Your File has no Data'
            ];
            return redirect()->back()->with(['validation_import_error' => $errors]);
        }

        if ($rowCount > 100) { // if the imported data rows from excel is over 100, then return back with error
            $errors[] = [
                'error' => 'Your imported data cannot be over 100 rows'
            ];
            return redirect()->back()->with(['validation_import_error' => $errors]);
        }

        foreach ($dataRows as $rowIndex => $row) {
            $rules = [ // to validate for each row and column in imported excel file
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

            $validator = Validator::make($row->toArray(), $rules, [], $this->attributeNames());
            if ($validator->fails()) { // if the validations are failed, store the validation errors in array
                $columnErrors = $validator->errors()->all();
                foreach ($columnErrors as $columnError) {
                    $errors[] = [
                        'error' =>  $columnError . ' in row ' . ($rowIndex + 1)
                    ];
                }
            }
            $birthDate = $row[6];
            if (!is_numeric($birthDate)) { // if birth date is not numeric, then return error messages
                $columnErrors = 'Birth date must be a valid date';
                $errors[] = [
                    'error' =>  $columnErrors . ' in row ' . ($rowIndex + 1)
                ];
                return redirect()->back()->with(['validation_import_error' => $errors]);
            }
            $formattedDate = Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
        }

        if (!empty($errors)) { // if errors exist, then return error messages

            return redirect()->back()->with(['validation_import_error' => $errors]);
        }

        try {
            foreach ($dataRows as $rowIndex => $row) {

                DB::beginTransaction();
                $employeeId = new EmployeeRepository;
                $employeeId = $employeeId->getNewEmployeeID(); // get new employee id for new imported data
                $newEmployee = new Employee(); // to store data from imported excel to employees table
                $newEmployee->employee_id = $employeeId;
                $newEmployee->employee_code = $row[0];
                $newEmployee->employee_name = $row[1];
                $newEmployee->nrc_number = $row[2];
                $newEmployee->password = Hash::make($row[3]);
                $newEmployee->email_address = $row[4];
                $newEmployee->gender = $row[5];
                $newEmployee->date_of_birth = $formattedDate;
                $newEmployee->marital_status = $row[7];
                $newEmployee->address = $row[8];

                DB::commit();
                $newEmployee->save();
                $employeeId++;
            }
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e) { // if the validations are failed, store the validation errors in array
                $columnErrors = 'Email address cannot be the same';
                $errors[] = [
                    'error' =>  $columnErrors . ' in row ' . ($rowIndex + 1)
                ];
            }
            return redirect()->back()->with(['validation_import_error' => $errors]);
        }
        return $newEmployee;
    }
}
