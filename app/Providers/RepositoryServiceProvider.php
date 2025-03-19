<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\StatsRepository;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Interfaces\TagRepositoryInterface;
use App\Repositories\EnrollmentRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\StatsRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EnrollmentRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(StatsRepositoryInterface::class,StatsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
