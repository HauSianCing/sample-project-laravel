<?php

namespace App\Http\Controllers;

use \Mpdf\Mpdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Interfaces\EmployeeInterface;

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
        $documentFileName = "search_results_employees.pdf";

        $tempDir = storage_path('framework/cache/mpdf');
        File::ensureDirectoryExists($tempDir);

        $document = new PDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => $tempDir,
        ]);

        $html = View::make('employees.searchPDF')
            ->with('employees', $employees)
            ->render();
        $document->WriteHTML($html);

        // Build the PDF in memory and send it as a download
        // (no temporary file left behind in storage).
        return response($document->Output($documentFileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $documentFileName . '"',
        ]);
    }
}
