<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Hash;
use App\Interfaces\EmployeeInterface;

use Illuminate\Support\Facades\Session;
use App\Http\Requests\EmployeeStoreRequest;
use App\Interfaces\EmployeeUploadInterface;
use App\Http\Requests\EmployeeUpdateRequest;
use App\DBTransactions\Employee\SaveEmployee;
use App\DBTransactions\Employee\DeleteEmployee;
use App\DBTransactions\Employee\UpdateEmployee;
use App\DBTransactions\EmployeeUpload\SaveEmployeeUpload;
use App\DBTransactions\EmployeeUpload\DeleteEmployeeUpload;
use App\DBTransactions\EmployeeUpload\UpdateEmployeeUpload;

/**
 * Class EmployeeController
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 21/6/2023
 */
class EmployeeController extends Controller
{
    /**
     * Display a constructor for the employee
     *@author Hau Sian Cing
     * @created 21/6/2023
     *
     */
    protected $employeeInterface;
    protected $employeeUploadInterface;

    /**
     * Constructor to assign interface to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $employeeInterface,employeeUploadInterface
     */
    public function __construct(EmployeeInterface $employeeInterface, EmployeeUploadInterface $employeeUploadInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->employeeUploadInterface = $employeeUploadInterface;
    }
    /**
     * Display the form for login
     *@author Hau Sian Cing
     * @created 21/6/2023
     * @return object
     */
    public function index(Request $request)
    {
        $employees = $this->employeeInterface->getAllEmployees();

        if ($request) {
            $employees = $this->employeeInterface->getSearchResult($request);
            if ($employees->currentPage() > $employees->lastPage()) {
                $page = $employees->lastPage();
                $employees = $this->employeeInterface->getSearchPage($request, $page);

                if (!empty(Session::get('success'))) {
                    $success = Session::get('success');
                    Session::forget('error');
                    return redirect()->to($request->fullUrlWithQuery(compact('page')))->with('status', $success);
                }
                if (!empty(Session::get('error'))) {
                    $error = Session::get('error');
                    Session::forget('success');
                    return redirect()->to($request->fullUrlWithQuery(compact('page')))->with('errors', $error);
                }

                return redirect()->to($request->fullUrlWithQuery(compact('page')));
            }
            return view('employees.lists', compact('employees'));
        }

        return view('employees.lists', compact('employees'));
    }

    /**
     * Checking the employee 
     *@author Hau Sian Cing
     * @created 21/6/2023
     * @param \App\Http\Requests\LoginRequest $request
     * @return object
     */
    public function checkEmp(LoginRequest $request)
    {
        $employee = Employee::where('employee_id', $request->employee_id)->first();

        if (!($employee && Hash::check($request->emp_password, $employee->password))) {
            return redirect()->route('login')->withErrors('Employee ID or Password is wrong');
        }
        session()->put('employee', $employee);
        return redirect()->route('employees.lists');
    }

    /**
     * Creating a new employee
     *@author Hau Sian Cing
     * @created 22/6/2023
     * @return object
     */
    public function create()
    {
        $newId = $this->employeeInterface->getNewEmployeeID();
        return view('employees.register', ['id' => $newId]);
    }

    /**
     * Store employee's information
     * @author Hau Sian Cing
     * @created 22/6/2023
     * @param  \App\Http\Requests\EmployeeStoreRequest $request
     * @return object
     */
    public function store(EmployeeStoreRequest $request)
    {
        try {
            $file = $request->file('photo');
            # get generated new employee id 
            $newId = $this->employeeInterface->getNewEmployeeID();
            $saveEmployee = new SaveEmployee($request);
            $saveEmployee = $saveEmployee->executeProcess();

            if ($file) {
                $saveUpload = new SaveEmployeeUpload($file, $newId);
                $saveUpload = $saveUpload->executeProcess();
            }
            if ($saveEmployee || $saveUpload) {
                return redirect()->route('employees.lists')->with('status', ' Employee ID ' . $newId . ' is created');
            } else {
                return  redirect()->back()->with('errors', 'Unable to save Employee Information');
            }
        } catch (\Exception $e) {
            return redirect()->route('employees.register')->with('errors', 'Something went Wrong. Please Try again');
        }
    }

    /**
     * Display the specified employee by id.
     *@author Hau Sian Cing
     * @created 03/07/2023
     * @param  $id
     * @return object
     */
    public function show($id)
    {
        $employee = $this->employeeInterface->getEmployeeByID($id);
        if (!$employee) {
            Session::put('error', 'No Employee Found to show');
            Session::forget('success');
            return redirect()->back()->with('errors', 'No Employee Found to show');
        }
        $employee_id = $employee->employee_id;
        $employeeUploads = $this->employeeUploadInterface->getEmployeeUploadByID($employee_id);

        $employeePhoto = null;
        if ($employeeUploads) {
            foreach ($employeeUploads as $employeeUpload) {
                $employeePhoto = $employeeUpload->file_name;
            }
        }
        return view('employees.detail', compact('employee', 'employeePhoto'));
    }

    /**
     * Show the form for editing the specified resource.
     *@author Hau Sian Cing
     * @created 03/07/2023
     * @param  $id
     * @return object
     */
    public function edit($id)
    {
        $previousUrl = url()->previous();
        $currentUrl =  url()->current();
        if ($previousUrl !== $currentUrl) {
            session(["previous_url_$id" => $previousUrl]);
        }

        $employee = $this->employeeInterface->getEmployeeByID($id);
        $previous = session("previous_url_$id");
        if (!$employee) {
            Session::put('error', 'No employee found to edit');
            Session::forget('success');
            return redirect()->to($previous)->with('errors', 'No employee found to edit');
        }
        if (!$employee->deleted_at == null) {
            Session::put('error', 'Unable to edit this employee');
            Session::forget('success');
            return redirect()->to($previous)->with('errors', 'Unable to edit this employee');
        }
        $employee_id = $employee->employee_id;
        $employeeUploads = $this->employeeUploadInterface->getEmployeeUploadByID($employee_id);
        $employeePhoto = null;
        if ($employeeUploads) {
            foreach ($employeeUploads as $employeeUpload) {
                $employeePhoto = $employeeUpload->file_name;
            }
        }
        return view('employees.edit', compact('employee', 'employeePhoto'));
    }

    /**
     * Update the specified resource in storage.
     *@author Hau Sian Cing
     * @created 04/07/2023
     * @param  \App\Http\Requests\EmployeeUpdateRequest $request;
     * @param  $id;
     * @return mixed
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        $previousUrl = session("previous_url_$id");
        $employee = $this->employeeInterface->getEmployeeByID($id);
        if (!$employee) {
            Session::put('error', 'Unable to upadate this employee');
            Session::forget('success');
            return redirect()->to($previousUrl)->with('errors', 'Unable to upadate this employee');
        }
        if (!$employee->deleted_at == null) {
            Session::put('error', 'Unable to update this employee');
            Session::forget('success');
            return redirect()->to($previousUrl)->with('errors', 'Unable to upadate this employee');
        }
        try {
            $updateEmployee = new UpdateEmployee($request);
            $updateEmployee = $updateEmployee->executeProcess();
            $employeeUploads = $this->employeeUploadInterface->getEmployeeUploadByID($request->employee_id);
            if (!$employeeUploads) {
                if ($request->file('photo')) {
                    $file = $request->file('photo');
                    $updateUpload = new UpdateEmployeeUpload($file, $request->employee_id);
                    $updateUpload = $updateUpload->executeProcess();
                }
            } else {
                if ($request->file('photo')) {
                    $file = $request->file('photo');
                    $deletephoto = new DeleteEmployeeUpload($employeeUploads);
                    $deletephoto = $deletephoto->executeProcess();
                    $updateUpload = new UpdateEmployeeUpload($file, $request->employee_id);
                    $updateUpload = $updateUpload->executeProcess();
                }
            }
            if ($updateEmployee || $updateUpload) {
                Session::put('success', 'Employee ID: ' . $request->employee_id . ' is updated successfully');
                Session::forget('error');
                return redirect()->to($previousUrl)->with('status', 'Employee ID: ' . $request->employee_id . ' is updated successfully');
            } else {
                Session::put('error', 'Unable to update this employee');
                Session::forget('success');
                return redirect()->to($previousUrl)->with('errors', 'Unable to update Employee Information');
            }
        } catch (\Exception $e) {
            Session::put('error', 'Unable to update this employee');
            Session::forget('success');
            return redirect()->to($previousUrl)->with('errors', 'Something went Wrong while updating. Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     *@author Hau Sian Cing
     * @created 03/07/2023
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        $employee = $this->employeeInterface->getEmployeeByID($id);
        if (!$employee) {
            Session::put('error', 'No employee Found to delete');
            Session::forget('success');
            return redirect()->back()->with('errors', 'No employee Found to delete');
        }

        $employeeEnter = session('employee');
        if ($employee->employee_id == $employeeEnter->employee_id) {

            return redirect()->back()->with('errors', 'You cannot delete your account while logging in');
        }

        $employeeDelete = new DeleteEmployee($employee->id);
        $employeeDelete = $employeeDelete->executeProcess();
        if ($employeeDelete == false) {
            Session::put('error', 'Unable to delete this employee.');
            Session::forget('success');
            return redirect()->back()->with('errors', 'Unable to delete this employee.');
        }
        Session::put('success', 'Employee ID: ' . $employee->employee_id . ' is deleted successfully.');
        Session::forget('error');
        return redirect()->back()->with('status', 'Employee ID: ' . $employee->employee_id . ' is deleted successfully.');
    }
    /**
     * active to inactive the employee 
     *@author Hau Sian Cing
     * @created 22/6/2023
     * @param  $employee_id
     * @return object
     */
    public function inactiveEmployee($employee_id)
    {
        $inActiveEmp = $this->employeeInterface->getEmployeeByID($employee_id);
        if (!$inActiveEmp) {
            Session::put('error', 'Unable to Inactivate this employee');
            Session::forget('success');
            return redirect()->back()->with('errors', 'Unable to Inactivate this employee');
        }
        $employeeEnter = session('employee');
        if ($inActiveEmp->employee_id == $employeeEnter->employee_id) {
            return redirect()->back()->with('errors', 'You cannot inactivate your account while logging in');
        }

        if ($inActiveEmp->deleted_at == null) {
            $inActiveEmp->delete(); // Soft delete the employee
            Session::put('success', 'Unable to Inactivate this employee');
            Session::forget('error');
            return redirect()->back()->with('status', 'Employee Inactivated successfully.');
        }
        Session::put('error', 'Unable to Inactivate this employee');
        Session::forget('success');
        return redirect()->back()->with('errors', 'Employee already Inactivated.');
    }
    /**
     * inactive to active the employee 
     *@author Hau Sian Cing
     * @created 22/6/2023
     * @param  $employee_id
     * @return object
     */
    public function activeEmployee($employee_id)
    {
        $activeEmp = $this->employeeInterface->getEmployeeByID($employee_id);
        if (!$activeEmp) {
            Session::put('error', 'Unable to Activate this employee');
            Session::forget('success');
            return redirect()->back()->with('errors', 'Unable to Activate this employee');
        }
        if ($activeEmp->deleted_at == null) {
            Session::put('error', 'Employee already Activated.');
            Session::forget('success');
            return redirect()->back()->with('errors', 'Employee already Activated.');
        }
        $activeEmp->restore(); //restore the soft deleted employee
        Session::put('success', 'Employee Activated successfully.');
        Session::forget('error');
        return redirect()->back()->with('status', 'Employee Activated successfully.');
    }
    /**
     * login the employee from login form
     *@author Hau Sian Cing
     * @created 21/6/2023
     * @return object
     */
    public function login()
    {
        return view('employees.login');
    }

    /**
     * logout and return to the login form
     *@author Hau Sian Cing
     * @created 21/6/2023
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
