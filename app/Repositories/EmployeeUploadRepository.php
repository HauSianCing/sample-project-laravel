<?php

namespace App\Repositories;


use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\DB;
use App\Interfaces\EmployeeUploadInterface;

/**
 * Class EmployeeUploadRepository
 * Handles employee photo functionality.
 *
 * @author Hau Sian Cing
 * @created 30/6/2023
 */
class EmployeeUploadRepository implements EmployeeUploadInterface
{

    /**
     * Getting all information for specific employee photo from employee_uploads table 
     *
     * @author Hau Sian Cing
     * @create 30/06/2023
     * @param $employee_id
     * @return object
     *
     */
    public function getEmployeeUploadByID($employee_id)
    {
        $employeeUploads = EmployeeUpload::withTrashed()
        ->where('employee_id', $employee_id)
        ->get();
        return $employeeUploads;
    }

   
}
