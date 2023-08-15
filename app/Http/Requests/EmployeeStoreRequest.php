<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
/**
 * Class EmployeeStoreRequest
 * Handles employee data functionality.
 *
 * @author Hau Sian Cing
 * @created 22/6/2023
 */
class EmployeeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @author Hau Sian Cing
     * @created 22/6/2023
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'unique:employees,employee_id',
            'employee_code' => 'required|max:25',
            'employee_name' =>'required|max:50',
            'nrc_number' => 'required|regex:/^[a-zA-Z0-9\/\(\)]+$/',
            'password' => 'required|string|min:4|max:8',
            'email_address' => 'required|email|unique:employees,email_address|max:50',
            'address' => 'max:500',
            'date_of_birth' => 'required|date|before:today',
            'photo'=> 'mimes:jpg,png,jpeg|max:10240'
        ];
    }

    /**
     * Get the validation messages to the request.
     * @author Hau Sian Cing
     * @created 22/6/2023
     * @return array
     */
    public function messages() {
        return [
            'employee_id.unique' => 'Employee ID cannot be the same',
            'employee_code.required' => 'Please enter Employee Code.',
            'employee_name.required' =>'Please enter Employee Name.',
            'nrc_number.required' => 'Please enter NRC Number.',
            'password.required' => 'Please enter Password.',
            'email_address.required' => 'Please enter Email Address.',
            'date_of_birth.required' => 'Please select your Birth Date.',
            'photo.image' => 'Your file must be Image'
        ];
    }
}
