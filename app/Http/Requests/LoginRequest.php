<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Class LoginRequest
 * Handles login functionality.
 *
 * @author Hau Sian Cing
 * @created 21/6/2023
 */
class LoginRequest extends FormRequest
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
        return [
            'employee_id' => 'required|integer',
            'emp_password' => 'required'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     * @author Hau Sian Cing
     * @created 03/07/2023
     * @return array
     */
    public function messages()
    {
        return [
            'employee_id.integer' => 'Employee ID must be integer',
            'employee_id.required' => 'You need to enter Employee ID.',
            'emp_password.required' => 'You need to enter Password.'
        ];
    }
}
