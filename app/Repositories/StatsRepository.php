<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;
use App\Interfaces\StatsRepositoryInterface;

class StatsRepository implements StatsRepositoryInterface
{
    // Statistiques des cours
    public function getCourseStats()
    {
        return [
            'total_courses' => Course::count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'inactive_courses' => Course::where('status', 'inactive')->count(),
        ];
    }

    // Statistiques des catégories
    public function getCategoryStats()
    {
        return [
            'total_categories' => Category::count(),
            'categories_with_courses' => Category::has('courses')->count(),
        ];
    }

    // Statistiques des tags
    public function getTagStats()
    {
        return [
            'total_tags' => Tag::count(),
            'most_used_tag' => Tag::withCount('courses')->orderByDesc('courses_count')->first(),
        ];
    }
}