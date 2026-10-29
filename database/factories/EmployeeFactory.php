<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $nextId = 10001;

        return [
            'employee_id' => $nextId++,
            'employee_code' => 'EMP' . fake()->unique()->numerify('####'),
            'employee_name' => fake()->name(),
            'nrc_number' => '12/abcd(N)' . fake()->numerify('######'),
            'password' => Hash::make('password'),
            'email_address' => fake()->unique()->safeEmail(),
            'gender' => fake()->randomElement([1, 2]),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'marital_status' => fake()->randomElement([1, 2, 3, null]),
            'address' => fake()->address(),
        ];
    }
}
