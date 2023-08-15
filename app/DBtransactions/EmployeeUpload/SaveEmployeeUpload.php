<?php

namespace App\DBTransactions\EmployeeUpload;

use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\Hash;

/**
 * Class SaveEmployeeUpload
 * 
 * @author  Hau Sian Cing
 * @create  22/06/2023
 */
class SaveEmployeeUpload extends DBTransaction
{
    private $file;
    private $id;


    /**
     * Constructor to assign  passing data to variable
     * @author  Hau Sian Cing
     * @create 03/07/2023
     * @param $request
     */
    public function __construct($file,$id)
    {
        $this->file = $file;
        $this->id = $id;
        
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
        $newUpload = new EmployeeUpload();
        $newUpload->employee_id =  $this->id;
        $file_name = $this->id.'_'.$file->getClientOriginalName();
        $filePath = 'photo/';
        $newUpload->file_extension = $file->getClientOriginalExtension();
        $newUpload->file_path = $filePath;
        $newUpload->file_name = $file_name;
        $newUpload->file_size = $file->getSize();
        $file->move($filePath, $file_name);
        $newUpload->save();

        // Save photo 
        $newUpload->save();
        if (!$newUpload) {
            return redirect()->back()->withErrors('');
        }
        return ['status' => true, 'error' => ''];
    }
}
