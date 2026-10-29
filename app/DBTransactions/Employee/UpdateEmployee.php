<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;

/**
 * Class UpdateEmployee
 *
 * @author  Hau Sian Cing
 * @create  04/07/2023
 */
class UpdateEmployee extends DBTransaction
{
    private $request;
    private $id;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 04/07/2023
     * @param $request
     * @param $id  primary key of the employee to update (taken from the route)
     */
    public function __construct($request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Update the employee
     * @author  Hau Sian Cing
     * @create 04/07/2023
     * @return array
     */
    public function process()
    {
        $request = $this->request;
        $employee = Employee::findOrFail($this->id);
        $employee->employee_code = $request->employee_code;
        $employee->employee_name = $request->employee_name;
        $employee->nrc_number = $request->nrc_number;
        $employee->email_address = $request->email_address;
        $employee->gender = $request->gender;
        $employee->date_of_birth = $request->date_of_birth;
        // 0 means "not selected"
        $employee->marital_status = $request->marital_status ? $request->marital_status : null;
        $employee->address = $request->address;

        if (!$employee->save()) {
            return ['status' => false, 'error' => 'Employee could not be updated'];
        }
        return ['status' => true, 'error' => ''];
    }
}
