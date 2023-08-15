<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'EmployeeController@login')->name('login');
Route::get('/logout', 'EmployeeController@logout')->name('logout');
Route::post('/employees/check', 'EmployeeController@checkEmp')->name('employees.check');
Route::post('/language', 'LanguageChangeController@switchLanguage')->name('language.switch');

Route::middleware(['lang', 'empCheck'])->group(function () {

    Route::get('/employees/lists', 'EmployeeController@index')->name('employees.lists');
    Route::get('/employees/searchs','EmployeeController@search')->name('employees.searchs');

    Route::get('/employees/pdf-search-downloads', 'EmployeeSearchPDFController@downloadPDFSearch')->name('employees.pdf-search-downloads');
    Route::get('/employees/excel-downloads', 'EmployeeExcelFormController@exportExcelResult')->name('employees.excel-downloads');

    Route::get('/employees/show/{id}', 'EmployeeController@show')->name('employees.show');
    Route::get('/employees/edit/{id}', 'EmployeeController@edit')->name('employees.edit');
    Route::put('/employees/update/{id}', 'EmployeeController@update')->name('employees.update');
    Route::delete('/employees/delete/{id}', 'EmployeeController@destroy')->name('employees.delete');

    Route::get('/employees/register', 'EmployeeController@create')->name('employees.register');
    Route::get('/employees/register/excel-registers', 'EmployeeExcelFormController@importExcelView')->name('employees.excel-registers');

    Route::post('/employees/store', 'EmployeeController@store')->name('employees.store');

    Route::post('/employees/inactive/{id}', 'EmployeeController@inactiveEmployee')->name('employees.inactive');
    Route::post('/employees/active/{id}', 'EmployeeController@activeEmployee')->name('employees.active');

    Route::get('/employees/export-excels', 'EmployeeExcelFormController@exportForm')->name('employees.export-excels');
    Route::post('/employees/import-excels', 'EmployeeExcelFormController@importExcel')->name('employees.import-excels');
});

Route::get('/', 'EmployeeController@login')->name('login');
