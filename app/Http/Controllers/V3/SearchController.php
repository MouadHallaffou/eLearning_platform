<?php

namespace App\Http\Controllers\V3;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class SearchController extends Controller
{
    // GET /api/V3/courses?search=
    public function searchCourses(Request $request)
    {
        $query = $request->input('search');
        
        $courses = Course::with(['category', 'mentor'])
            ->where('title', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->get();

        return response()->json($courses);
    }

    // GET /api/V3/courses/filter?category_id=&difficulty=
    public function filterCourses(Request $request)
    {
        $courses = Course::with(['category', 'mentor']);

        if ($request->has('category_id')) {
            $courses->where('category_id', $request->category_id);
        }

        if ($request->has('difficulty')) {
            $courses->where('level', $request->difficulty);
        }

        return response()->json($courses->get());
    }

    // GET /api/V3/mentors?search=
    public function searchMentors(Request $request)
    {
        $query = $request->input('search', '');

        return User::with(['courses' => fn($q) => $q->select('id', 'title', 'user_id')])
            ->whereHas('roles', fn($q) => $q->where('name', 'mentor'))
            ->when($query, fn($q) => $q->where('name', 'LIKE', "%$query%"))
            ->get(['id', 'name']);
    }

    // GET /api/V3/students?badges=
    // public function filterStudentsByBadge(Request $request)
    // {
    //     return response()->json(
    //         $this->searchRepo->filterStudentsByBadge($request->input('badges'))
    //     );
    // }
}