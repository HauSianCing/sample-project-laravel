<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\Hash;

/**
 * Class SaveEmployee
 *
 * @author  Hau Sian Cing
 * @create  22/06/2023
 */
class SaveEmployee extends DBTransaction
{
    private $request;
    private $employeeId;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     * @param $employeeId  server-generated employee ID (falls back to the request value)
     */
    public function __construct($request, $employeeId = null)
    {
        $this->request = $request;
        $this->employeeId = $employeeId;
    }

    /**
     * Save a new employee
     * @author  Hau Sian Cing
     * @create 22/06/2023
     * @return array
     */
    public function process()
    {
        $request = $this->request;
        $newEmployee = new Employee();
        $newEmployee->employee_id = $this->employeeId ?? $request->employee_id;
        $newEmployee->employee_code = $request->employee_code;
        $newEmployee->employee_name = $request->employee_name;
        $newEmployee->nrc_number = $request->nrc_number;
        $newEmployee->password = Hash::make($request->password);
        $newEmployee->email_address = $request->email_address;
        $newEmployee->gender = $request->gender;
        $newEmployee->date_of_birth = $request->date_of_birth;
        // 0 means "not selected"
        $newEmployee->marital_status = $request->marital_status ? $request->marital_status : null;
        $newEmployee->address = $request->address;

        if (!$newEmployee->save()) {
            return ['status' => false, 'error' => 'Employee could not be saved'];
        }
        return ['status' => true, 'error' => ''];
    }
}
