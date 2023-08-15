<?php

namespace App\DBTransactions\EmployeeUpload;
use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\File;

/**
 * Class DeleteEmployeeUpload
 * 
 * @author  Hau Sian Cing
 * @create  03/07/2023
 */
class DeleteEmployeeUpload extends DBTransaction
{
    private $employee;
    /**
     * Constructor to assign  passing data to variable
     * @create 03/07/2023
     * @param $request
     */
    public function __construct($request)
    {
        $this->employee = $request;
    }

    /**
     * deleting employee upload info: from the employee_uploads table
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @return  array
     */
    public function process()
    {
        $records = EmployeeUpload::find($this->employee);
        
            if ($records) {
                foreach ($records as $result) {
                    $photoPath = public_path('photo/' . $result->file_name);
                    if (File::exists($photoPath)) {
                        File::delete($photoPath);
                    }
                    $result->forceDelete();
                    if($result) {
                        return ['status' => true, 'error' => ''];
                    }
                    return ['status' => '', 'error' => true];

                }
               
            }
        
    }
}
