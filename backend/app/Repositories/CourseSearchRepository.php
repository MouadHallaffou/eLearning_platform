<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use App\Interfaces\CourseSearchRepositoryInterface;

class CourseSearchRepository implements CourseSearchRepositoryInterface 
{
    public function searchCourses($query)
    {
        return Course::with(['category', 'mentor'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%'.$query.'%')
                  ->orWhere('description', 'like', '%'.$query.'%');
            });
    }

    public function filterCourses($categoryId, $difficulty)
    {
        return Course::with(['category', 'mentor'])
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($difficulty, fn($q) => $q->where('level', $difficulty));
    }

    public function searchMentors($query)
    {
        return User::with('roles')
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->when($query, fn($q) => $q->where('name', 'LIKE', "%$query%"))
            ->get(['id', 'name']);
    }

    public function searchMentorsWithCourses($query)
    {
        return User::with(['courses' => fn($q) => $q->select('id', 'title', 'user_id')])
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->when($query, fn($q) => $q->where('name', 'LIKE', "%$query%"))
            ->get(['id', 'name']);
    }

    public function filterStudentsByBadge($badgeId)
    {
        return User::role('student')
            ->whereHas('badges', fn($q) => $q->where('badges.id', $badgeId))
            ->with('badges');
    }
}