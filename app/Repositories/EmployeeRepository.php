<?php

namespace App\Repositories;


use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Interfaces\EmployeeInterface;
use Mpdf\Tag\Em;

/**
 * Class EmployeeRepository
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 22/6/2023
 */
class EmployeeRepository implements EmployeeInterface
{
    /**
     * Getting all information for all employees from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @return object
     *
     */
    public function getAllEmployees()
    {
        $employees = Employee::withTrashed()->paginate(20);

        return $employees;
    }

    /**
     * Getting all information for specific employee from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @param $id
     * @return object
     *
     */
    public function getEmployeeByID($id)
    {

        return $employee = Employee::withTrashed()->find($id);
    }

    /**
     * Getting new ID  for new employee from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @return integer
     *
     */
    public function getNewEmployeeID()
    {

        $maxEmployee = DB::table('employees')->max('employee_id');
        if ($maxEmployee) {
            $addID = $maxEmployee + 1;
            return $addID;
        } else {
            $addID = 10001;
            return $addID;
        }
    }

    /**
     * Searching by the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     * @return object
     *
     */
    public function getSearchResultForDownload($request = null)
    {
        $searchID = $request->employeeID;
        $searchCode = $request->employeeCode;
        $searchName = $request->employeeName;
        $searchEmail = $request->employeeEmail;
        $employees = Employee::query();

        $query = $employees->withTrashed();
        $employees = $query

            ->when(!empty($searchID), function ($query) use ($searchID) {

                $query->where('employee_id',  'LIKE', '%' . $searchID . '%');
            })

            ->when(!empty($searchName), function ($query) use ($searchName) {

                $query->where('employee_name', 'LIKE', '%' . $searchName . '%');
            })

            ->when(!empty($searchCode), function ($query) use ($searchCode) {

                $query->where('employee_code', 'LIKE', '%' . $searchCode . '%');
            })
            ->when(!empty($searchEmail), function ($query) use ($searchEmail) {

                $query->where('email_address', 'LIKE', '%' . $searchEmail . '%');
            });
            $employees = $employees->get();
        return $employees;
    }
    /**
     * Searching by the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     * @return object
     *
     */
    public function getSearchResult($request = null)
    {
        $searchID = $request->employeeID;
        $searchCode = $request->employeeCode;
        $searchName = $request->employeeName;
        $searchEmail = $request->employeeEmail;
        $employees = Employee::query();

        $query = $employees->withTrashed();
        $employees = $query

            ->when(!empty($searchID), function ($query) use ($searchID) {

                $query->where('employee_id',  'LIKE', '%' . $searchID . '%');
            })

            ->when(!empty($searchName), function ($query) use ($searchName) {

                $query->where('employee_name', 'LIKE', '%' . $searchName . '%');
            })

            ->when(!empty($searchCode), function ($query) use ($searchCode) {

                $query->where('employee_code', 'LIKE', '%' . $searchCode . '%');
            })
            ->when(!empty($searchEmail), function ($query) use ($searchEmail) {

                $query->where('email_address', 'LIKE', '%' . $searchEmail . '%');
            });
        $employees = $employees->paginate(20)->appends([
            'employeeID' => $searchID,
            'employeeCode' => $searchCode,
            'employeeName' => $searchName,
            'employeeEmail' => $searchEmail
        ]);
        return $employees;
    }

    /**
     * Searching by the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 03/07/2023
     * @param $request, $page
     * @return object
     *
     */
    public function getSearchPage($request = null, $page = null)
    {
        $searchID = $request->employeeID;
        $searchCode = $request->employeeCode;
        $searchName = $request->employeeName;
        $searchEmail = $request->employeeEmail;
        $employees = Employee::query();

        $query = $employees->withTrashed();
        $employees = $query

            ->when(!empty($searchID), function ($query) use ($searchID) {

                $query->where('employee_id',  'LIKE', '%' . $searchID . '%');
            })

            ->when(!empty($searchName), function ($query) use ($searchName) {

                $query->where('employee_name', 'LIKE', '%' . $searchName . '%');
            })

            ->when(!empty($searchCode), function ($query) use ($searchCode) {

                $query->where('employee_code', 'LIKE', '%' . $searchCode . '%');
            })
            ->when(!empty($searchEmail), function ($query) use ($searchEmail) {

                $query->where('email_address', 'LIKE', '%' . $searchEmail . '%');
            });

        $employees = $employees->paginate(20, ['*'], 'page', $page)->appends([
            'employeeID' => $searchID,
            'employeeCode' => $searchCode,
            'employeeName' => $searchName,
            'employeeEmail' => $searchEmail
        ]);
        return $employees;
    }
}
