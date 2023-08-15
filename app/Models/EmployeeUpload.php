<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class EmployeeUpload
 * Handles employee upload data functionality.
 *
 * @author Hau Sian Cing
 * @created 04/07/2023
 */
class EmployeeUpload extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
