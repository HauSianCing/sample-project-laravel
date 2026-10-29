<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeExcelFormController;
use App\Http\Controllers\EmployeeSearchPDFController;
use App\Http\Controllers\LanguageChangeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [EmployeeController::class, 'login']);
Route::get('/login', [EmployeeController::class, 'login'])->name('login');
Route::post('/employees/check', [EmployeeController::class, 'checkEmp'])->name('employees.check');
Route::post('/logout', [EmployeeController::class, 'logout'])->name('logout');
Route::post('/language', [LanguageChangeController::class, 'switchLanguage'])->name('language.switch');

Route::middleware(['lang', 'empCheck'])->group(function () {

    Route::get('/employees/lists', [EmployeeController::class, 'index'])->name('employees.lists');
    Route::get('/employees/searchs', [EmployeeController::class, 'index'])->name('employees.searchs');

    Route::get('/employees/pdf-search-downloads', [EmployeeSearchPDFController::class, 'downloadPDFSearch'])->name('employees.pdf-search-downloads');
    Route::get('/employees/excel-downloads', [EmployeeExcelFormController::class, 'exportExcelResult'])->name('employees.excel-downloads');

    Route::get('/employees/show/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.delete');

    Route::get('/employees/register', [EmployeeController::class, 'create'])->name('employees.register');
    Route::get('/employees/register/excel-registers', [EmployeeExcelFormController::class, 'importExcelView'])->name('employees.excel-registers');

    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');

    Route::post('/employees/inactive/{id}', [EmployeeController::class, 'inactiveEmployee'])->name('employees.inactive');
    Route::post('/employees/active/{id}', [EmployeeController::class, 'activeEmployee'])->name('employees.active');

    Route::get('/employees/export-excels', [EmployeeExcelFormController::class, 'exportForm'])->name('employees.export-excels');
    Route::post('/employees/import-excels', [EmployeeExcelFormController::class, 'importExcel'])->name('employees.import-excels');
});
