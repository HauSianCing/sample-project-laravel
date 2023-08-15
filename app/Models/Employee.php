<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Employee
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 04/07/2023
 */
class Employee extends Model
{ 
    use SoftDeletes;
    protected $guarded = [];
    
}
