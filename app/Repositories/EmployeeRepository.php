<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Interfaces\EmployeeInterface;

/**
 * Class EmployeeRepository
 * Data access for employees (search, lookup, new ID).
 *
 * @author Hau Sian Cing
 * @created 03/07/2023
 */
class EmployeeRepository implements EmployeeInterface
{
    /** Number of employees shown per page on the list screen. */
    const PER_PAGE = 20;

    /**
     * Get all employees (active and inactive), paginated
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllEmployees()
    {
        return Employee::withTrashed()->paginate(self::PER_PAGE);
    }

    /**
     * Find one employee by primary key, including inactive ones
     * @param $id
     * @return \App\Models\Employee|null
     */
    public function getEmployeeByID($id)
    {
        return Employee::withTrashed()->find($id);
    }

    /**
     * Next employee ID (starts at 10001)
     * @return int
     */
    public function getNewEmployeeID()
    {
        $maxEmployee = Employee::withTrashed()->max('employee_id');
        return $maxEmployee ? $maxEmployee + 1 : 10001;
    }

    /**
     * All search results (no pagination) for the Excel / PDF downloads
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getSearchResultForDownload($request = null)
    {
        return $this->searchQuery($request)->get();
    }

    /**
     * Paginated search results for the list screen
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSearchResult($request = null)
    {
        return $this->searchQuery($request)
            ->paginate(self::PER_PAGE)
            ->appends($this->searchParams($request));
    }

    /**
     * Paginated search results for a specific page
     * @param \Illuminate\Http\Request $request
     * @param int $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSearchPage($request = null, $page = null)
    {
        return $this->searchQuery($request)
            ->paginate(self::PER_PAGE, ['*'], 'page', $page)
            ->appends($this->searchParams($request));
    }

    /**
     * Search filters taken from the request
     * @param \Illuminate\Http\Request|null $request
     * @return array
     */
    private function searchParams($request)
    {
        return [
            'employeeID' => $request ? $request->employeeID : null,
            'employeeCode' => $request ? $request->employeeCode : null,
            'employeeName' => $request ? $request->employeeName : null,
            'employeeEmail' => $request ? $request->employeeEmail : null,
        ];
    }

    /**
     * Build the shared search query (active and inactive employees)
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchQuery($request)
    {
        $params = $this->searchParams($request);
        $columns = [
            'employeeID' => 'employee_id',
            'employeeCode' => 'employee_code',
            'employeeName' => 'employee_name',
            'employeeEmail' => 'email_address',
        ];

        $query = Employee::withTrashed();
        foreach ($columns as $param => $column) {
            $value = $params[$param];
            $query->when(!empty($value), function ($query) use ($column, $value) {
                $query->where($column, 'LIKE', '%' . $value . '%');
            });
        }
        return $query;
    }
}
