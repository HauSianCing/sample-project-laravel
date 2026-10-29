<?php

namespace App\DBTransactions\EmployeeUpload;

use Illuminate\Support\Str;
use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;

/**
 * Class SaveEmployeeUpload
 *
 * @author  Hau Sian Cing
 * @create  22/06/2023
 */
class SaveEmployeeUpload extends DBTransaction
{
    private $file;
    private $employeeId;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param \Illuminate\Http\UploadedFile $file
     * @param $employeeId
     */
    public function __construct($file, $employeeId)
    {
        $this->file = $file;
        $this->employeeId = $employeeId;
    }

    /**
     * storing the uploaded photo information to employee_uploads table
     * @author  Hau Sian Cing
     * @create 22/06/2023
     * @return  array
     */
    public function process()
    {
        $file = $this->file;
        $extension = strtolower($file->getClientOriginalExtension());
        // Never trust the client file name: keep a safe slug of it only.
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'photo';
        $fileName = $this->employeeId . '_' . $baseName . '.' . $extension;

        $upload = new EmployeeUpload();
        $upload->employee_id = $this->employeeId;
        $upload->file_extension = $extension;
        $upload->file_path = 'photo/';
        $upload->file_name = $fileName;
        $upload->file_size = $file->getSize();

        // Absolute path so it works from web requests, queues and tests alike.
        $file->move(public_path('photo'), $fileName);

        if (!$upload->save()) {
            return ['status' => false, 'error' => 'Photo could not be saved'];
        }
        return ['status' => true, 'error' => ''];
    }
}
