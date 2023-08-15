<?php

namespace App\Interfaces;

interface EmployeeInterface
{
    /**
     * Getting all information for all employees from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @return object
     *
     */
    public function getAllEmployees();

    /**
     * Getting all information for specific employee from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @param $id
     * @return object
     *
     */
    public function getEmployeeByID($id);

    /**
     * Getting new ID  for new employee from employees table 
     *
     * @author Hau Sian Cing
     * @create 22/06/2023
     * @return integer
     *
     */

    public function getNewEmployeeID();

    /**
     * Searching by the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 03/07/2023
     * @param $searchID,$searchName,$searchCode,$searchEmail
     * @return object
     *
     */
    public function getSearchResult($request);

    /**
     * get page  by searching the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 12/07/2023
     * @param $searchID,$searchName,$searchCode,$searchEmail
     * @return object
     *
     */
    public function getSearchPage($request , $page);
    /**
     * get page  by searching the input data for employee 
     *
     * @author Hau Sian Cing
     * @create 12/07/2023
     * @param $searchID,$searchName,$searchCode,$searchEmail
     * @return object
     *
     */
    public function getSearchResultForDownload($request = null);

}
