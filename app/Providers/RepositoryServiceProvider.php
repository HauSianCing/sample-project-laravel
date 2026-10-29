<?php

namespace App\Providers;

use App\Interfaces\EmployeeInterface;
use App\Interfaces\EmployeeUploadInterface;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeUploadRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * Binds the repository interfaces to their implementations.
 *
 * @author Hau Sian Cing
 * @created 03/07/2023
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array<class-string, class-string>
     */
    public $bindings = [
        EmployeeInterface::class => EmployeeRepository::class,
        EmployeeUploadInterface::class => EmployeeUploadRepository::class,
    ];
}
