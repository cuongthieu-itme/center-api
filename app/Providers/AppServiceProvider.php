<?php

namespace App\Providers;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\ClassModelRepositoryInterface;
use App\Interfaces\ClassSessionRepositoryInterface;
use App\Interfaces\StudentClassRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Repositories\AttendanceRepository;
use App\Repositories\AuthRepository;
use App\Repositories\ClassModelRepository;
use App\Repositories\ClassSessionRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(ClassModelRepositoryInterface::class, ClassModelRepository::class);
        $this->app->bind(StudentClassRepositoryInterface::class, StudentClassRepository::class);
        $this->app->bind(ClassSessionRepositoryInterface::class, ClassSessionRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
