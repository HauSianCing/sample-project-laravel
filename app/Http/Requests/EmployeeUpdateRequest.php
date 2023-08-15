<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class EmployeeUpdateRequest
 * Handles employee updated data functionality.
 *
 * @author Hau Sian Cing
 * @created 22/6/2023
 */
class EmployeeUpdateRequest extends FormRequest
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
     * @created 03/07/2023
     * @return array
     */
    public function rules()
    {
        $employeeId = $this->route('id');
        return [
            'employee_code' => 'required||max:25',
            'employee_name' => 'required||max:50',
            'nrc_number' => 'required|regex:/^[a-zA-Z0-9\/\(\)]+$/',
            'email_address' => ['required',
                'email',
                Rule::unique('employees')->ignore($employeeId, 'id'), 'max:50'
            ],
            'date_of_birth' => 'required|date|before:today',
            'address'=> 'max:500',
            'photo' => 'mimes:jpg,jpeg,png|max:10240'

        ];
    }
}
