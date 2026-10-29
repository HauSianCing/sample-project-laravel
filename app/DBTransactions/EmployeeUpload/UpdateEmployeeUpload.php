<?php

namespace App\DBTransactions\EmployeeUpload;

/**
 * Class UpdateEmployeeUpload
 * Saves the new photo when an employee is edited.
 * The old photo is removed beforehand with DeleteEmployeeUpload,
 * so this is the same operation as SaveEmployeeUpload.
 *
 * @author  Hau Sian Cing
 * @create  04/07/2023
 */
class UpdateEmployeeUpload extends SaveEmployeeUpload
{
}
