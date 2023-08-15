<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
/**
 * Class EmployeeSeeder
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 21/6/2023
 */
class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # This seeder file contains sample data for the 'employees' table
        $passwordHash = Hash::make('11111111');
        Employee::create([
            'employee_id' => '10001',
            'employee_code' => 'employee',
            'employee_name' => 'your name',
            'nrc_number'=>'4/tatana(N)123456',
            'password'=> $passwordHash,
            'email_address'=>'abcde@gmail.com',
            'gender'=>'2',
            'date_of_birth'=>'1999/04/12',
            'marital_status'=>'3',
            'address'=>'dkgafkhfakj',
            
        ]);
    }
}
