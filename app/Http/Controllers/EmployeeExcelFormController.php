<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ExcelSearchResults;
use App\Exports\ExcelExportEmployee;
use App\Imports\EmployeeExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\EmployeeExcelStoreRequest;

/**
 * Class EmployeeExcelFormController
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 23/06/2023
 */

class EmployeeExcelFormController extends Controller
{
    /**
     * Constructor to assign  interface to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $employeeInterface
     */
    protected $employeeInterface;
    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
    }
    /**
     * Importing employees' data from excel file
     *@author Hau Sian Cing
     * @created 23/06/2023
     * @param \Illuminate\Http\Request $request;
     * @return object
     */
    public function importExcel(Request $request)
    {

        $file = $request->file('excelFile');
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|mimes:xlsx'
        ], [
            'excelFile.required' => 'You need to upload an Excel File',
            'excelFile.mimes' => 'The uploaded file must be in XLSX format.'
        ]);

        if ($validator->fails()) { //if validator fails , return error
            $errors = $validator->errors()->all();
            return redirect()->back()->with(['validation_import_error' => $errors]);
        } else {
            $fileName = $file->getClientOriginalName();
            if (Str::contains($fileName, 'employee_registration_form') == false) {
                return redirect()->back()->with(['validation_import_error' => 'Your imported file is Wrong. Please check your file again!']);
            }
        }
        $data = Excel::import(new EmployeeExcelImport, $file); 
        return redirect()->back()->with('status', "The File is imported successfully");
    }

    /**
     * to get employee registration form as an excel file
     *@author Hau Sian Cing
     * @created 23/06/2023
     * @return object
     */
    public function exportForm()
    {
        return Excel::download(new ExcelExportEmployee, 'employee_registration_form.xlsx');
    }
    /**
     * to display excel registration view
     *@author Hau Sian Cing
     * @created 26/06/2023
     * @return object
     */
    public function importExcelView()
    {
        return view('employees.excelRegister');
    }

    /**
     * to download serach result as an excel file
     *@author Hau Sian Cing
     * @created 28/06/2023
     * @param \Illuminate\Http\Request $request;
     * @return object
     */
    public function exportExcelResult(Request $request)
    {
        
        $employees = $this->employeeInterface->getSearchResultForDownload($request);
        if ($employees) {

            $data = new ExcelSearchResults($employees);
            return Excel::download($data, 'search_employees_results.xlsx');
        }

        return redirect()->back()->with('errors', 'No Employee Found');
    }
}
