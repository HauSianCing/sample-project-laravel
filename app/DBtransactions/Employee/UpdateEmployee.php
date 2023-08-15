<?php

namespace App\DBTransactions\Employee;

use App\Models\Employee;
use App\Classes\DBTransaction;
use Illuminate\Support\Facades\Hash;

/**
 * Class UpdateEmployee
 * 
 * @author  Hau Sian Cing
 * @create  22/06/2023
 */
class UpdateEmployee extends DBTransaction
{
    private $request;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Storing updated data for employee to employees table      
     * @author  Hau Sian Cing
     * @create 22/06/2023
     * @return  array
     */
    public function process()
    {
        
        $request = $this->request;
        $employee = Employee::findOrFail($request->id); 
        $employee->employee_code = $request->employee_code;
        $employee->employee_name = $request->employee_name;
        $employee->nrc_number = $request->nrc_number;
        $employee->email_address = $request->email_address;
        $employee->gender = $request->gender;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->marital_status = $request->marital_status;
        $employee->address = $request->address;

        // Save employee
        $employee->save();
        if (!$employee) {
            return redirect()->back()->withErrors('');
        }
        return ['status' => true, 'error' => ''];
    }
}
