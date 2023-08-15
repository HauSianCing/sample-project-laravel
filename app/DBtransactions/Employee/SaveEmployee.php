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
     * storing data for employee to employees table        
     * @author  Hau Sian Cing
     * @create 22/06/2023
     * @return  array
     */
    public function process()
    {
        $request = $this->request;
        $newEmployee = new Employee();
        $newEmployee->employee_id = $request->employee_id;
        $newEmployee->employee_code = $request->employee_code;
        $newEmployee->employee_name = $request->employee_name;
        $newEmployee->nrc_number = $request->nrc_number;
        $newEmployee->password = Hash::make($request->password);
        $newEmployee->email_address = $request->email_address;
        $newEmployee->gender = $request->gender;
        $newEmployee->date_of_birth = $request->date_of_birth;
        if($request->marital_status== 0) {
            $newEmployee->marital_status = null;
        }
        $newEmployee->marital_status = $request->marital_status;
        $newEmployee->address = $request->address;

        // Save employee
        $newEmployee->save();
        if (!$newEmployee) {
            return redirect()->back()->withErrors('');
        }
        return ['status' => true, 'error' => ''];
    }
}
