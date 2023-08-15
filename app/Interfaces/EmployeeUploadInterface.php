<?php

namespace App\Interfaces;

interface EmployeeUploadInterface
{
    /**
     * Getting all information for specific employee photo from employee_uploads table 
     *
     * @author Hau Sian Cing
     * @create 30/06/2023
     * @param $employee_id
     * @return object
     *
     */
    public function getEmployeeUploadByID($empoyee_id);

   
}