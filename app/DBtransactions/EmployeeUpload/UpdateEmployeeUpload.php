<?php

namespace App\DBTransactions\EmployeeUpload;

use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\Hash;

/**
 * Class UpdateEmployeeUpload
 * 
 * @author  Hau Sian Cing
 * @create  /06/2023
 */
class UpdateEmployeeUpload extends DBTransaction
{
    private $file;
    private $employee_id;

    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $file,$employee_id
     */
    public function __construct($file,$employee_id)
    {
        $this->file = $file;
        $this->employee_id = $employee_id;
    }

    /**
     * Storing the updated photo information to employee_uploads table        
     * @author  Hau Sian Cing
     * @create 22/06/2023
     * @return  array
     */
    public function process()
    {
        // 
        $file = $this->file;
        $updateUpload = new EmployeeUpload();
        $updateUpload->employee_id = $this->employee_id;
        $file_name = $this->employee_id.'_'.$file->getClientOriginalName();
        $filePath = 'photo/';
        $updateUpload->file_extension = $file->getClientOriginalExtension();
        $updateUpload->file_path = $filePath;
        $updateUpload->file_name = $file_name;
        $updateUpload->file_size = $file->getSize();
        $file->move($filePath, $file_name);
        $updateUpload->save();

        // Save photo 
        $updateUpload->save();
        if (!$updateUpload) {
            return redirect()->back()->withErrors('');
        }
        return ['status' => true, 'error' => ''];
    }
}
