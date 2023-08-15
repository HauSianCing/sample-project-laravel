<?php

namespace App\Http\Controllers;

use \Mpdf\Mpdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Interfaces\EmployeeInterface;
use Illuminate\Support\Facades\Storage;

/**
 * Class EmployeeSearchPDFController
 * Handles employee search data functionality.
 *
 * @author Hau Sian Cing
 * @created 03/7/2023
 */
class EmployeeSearchPDFController extends Controller
{
    protected $employeeInterface;
    /**
     * Constructor to assign interface to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $employeeInterface
     */
    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
    }

    /**
     * Download for search results  of the employee as PDF format
     *@author Hau Sian Cing
     * @created 03/07/2023
     * @param \Illuminate\Http\Request $request
     * @return mixed
     *
     */
    public function downloadPDFSearch(Request $request)
    {
        $employees = $this->employeeInterface->getSearchResultForDownload($request);
        $documentFileName = "search_results_emplpyees.pdf";

        $document = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4'
        ]);

        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
        ];

        $html = View::make('employees.searchPDF')
            ->with('employees', $employees);
        $document->WriteHTML($html);

        // Save PDF on your public storage 
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "D"));

        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //
    }
}
