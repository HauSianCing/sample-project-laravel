<?php

namespace App\Providers;

use App\Interfaces\EmployeeInterface;
use App\Interfaces\EmployeeUploadInterface;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeUploadRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        EmployeeInterface::class => EmployeeRepository::class,
        EmployeeUploadInterface::class => EmployeeUploadRepository::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(EmployeeUploadInterface::class, EmployeeUploadRepository::class);
    }

}
