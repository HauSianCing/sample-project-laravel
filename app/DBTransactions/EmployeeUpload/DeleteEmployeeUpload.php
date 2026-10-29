<?php

namespace App\DBTransactions\EmployeeUpload;

use App\Classes\DBTransaction;
use App\Models\EmployeeUpload;
use Illuminate\Support\Facades\File;

/**
 * Class DeleteEmployeeUpload
 *
 * @author  Hau Sian Cing
 * @create  03/07/2023
 */
class DeleteEmployeeUpload extends DBTransaction
{
    private $uploads;

    /**
     * Constructor to assign  passing data to variable
     * @create 03/07/2023
     * @param \Illuminate\Support\Collection|\App\Models\EmployeeUpload $uploads
     */
    public function __construct($uploads)
    {
        $this->uploads = $uploads;
    }

    /**
     * Delete every given upload record and its photo file
     * @create 03/07/2023
     * @return array
     */
    public function process()
    {
        $uploads = $this->uploads instanceof EmployeeUpload ? collect([$this->uploads]) : collect($this->uploads);

        if ($uploads->isEmpty()) {
            return ['status' => false, 'error' => 'No upload found to delete'];
        }

        foreach ($uploads as $upload) {
            $photoPath = public_path('photo/' . $upload->file_name);
            if (File::exists($photoPath)) {
                File::delete($photoPath);
            }
            $upload->forceDelete();
        }
        return ['status' => true, 'error' => ''];
    }
}
