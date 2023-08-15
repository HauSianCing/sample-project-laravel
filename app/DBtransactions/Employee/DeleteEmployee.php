<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Class DeleteEmployee
 * 
 * @author  Hau Sian Cing
 * @create  03/07/2023
 */
class DeleteEmployee extends DBTransaction
{
    private $employee;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     */
    public function __construct($request)
    {
        $this->employee = $request;
    }

    /**
     * deleting employee data from the employees table    
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @return  array
     */
    public function process()
    {
        $record = Employee::find($this->employee);
        if ($record) {
            $uploadResult = EmployeeUpload::where('employee_id', $record->employee_id)->get();
            if ($uploadResult) {
                foreach ($uploadResult as $result) {
                    $photoPath = public_path('photo/' . $result->file_name);
                    if (File::exists($photoPath)) {
                        File::delete($photoPath);
                    }
                    $result->forceDelete();
                }
            }
            $record->forceDelete();
                return ['status' => true, 'error' => ''];
        }
        return ['status' => false, 'error' => true];
    }
}
