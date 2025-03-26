<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CourseSearchRepository
{
    // GET /api/V3/courses?search=
    public function searchCourses($query)
    {
        return Course::where('title', 'like', '%'.$query.'%')
                   ->orWhere('description', 'like', '%'.$query.'%')
                   ->with(['category', 'mentor']);
    }

    // GET /api/V3/courses/filter?category=&difficulty=
    public function filterCourses($categoryId, $difficulty)
    {
        $query = Course::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($difficulty) {
            $query->where('difficulty_level', $difficulty);
        }

        return $query->with(['category', 'mentor']);
    }

    // GET /api/V3/mentors?search=
public function searchMentors(Request $request)
{
    $query = $request->input('search');
    
    // Solution 100% fiable avec jointure directe
    $mentors = User::whereHas('roles', function($q) {
            $q->where('name', 'mentor');
        })
        ->where('name', 'LIKE', "%$query%")
        ->get(['id', 'name']);

    return response()->json($mentors);
}

    // GET /api/V3/students?badges=
    public function filterStudentsByBadge($badgeId)
    {
        return User::where('role', 'student')
                 ->whereHas('badges', function($q) use ($badgeId) {
                     $q->where('badges.id', $badgeId);
                 })
                 ->with('badges');
    }
}