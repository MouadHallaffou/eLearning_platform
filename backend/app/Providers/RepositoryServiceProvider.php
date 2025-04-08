<?php

namespace App\Providers;

use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\BadgeRepository;
use App\Repositories\StatsRepository;
use App\Repositories\VideoRepository;
use App\Repositories\CourseRepository;
use App\Repositories\MentorRepository;
use App\Repositories\StudentRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Interfaces\TagRepositoryInterface;
use App\Repositories\EnrollmentRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\BadgeRepositoryInterface;
use App\Interfaces\StatsRepositoryInterface;
use App\Interfaces\VideoRepositoryInterface;
use App\Repositories\CourseSearchRepository;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\MentorRepositoryInterface;
use App\Interfaces\StudentRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EnrollmentRepositoryInterface;
use App\Interfaces\CourseSearchRepositoryInterface;

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
        $this->app->bind(StatsRepositoryInterface::class, StatsRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(MentorRepositoryInterface::class, MentorRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(CourseSearchRepositoryInterface::class, CourseSearchRepository::class);
        $this->app->bind(BadgeRepositoryInterface::class,BadgeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
