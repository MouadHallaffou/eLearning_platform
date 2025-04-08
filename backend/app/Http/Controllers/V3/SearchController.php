<?php

namespace App\Http\Controllers\V3;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CourseSearchRepositoryInterface;

class SearchController extends Controller
{
    private $searchRepository;

    public function __construct(CourseSearchRepositoryInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    // GET /api/V3/courses?search=
    public function searchCourses(Request $request)
    {
        $courses = $this->searchRepository->searchCourses($request->input('search'))->get();
        return response()->json($courses);
    }

    // GET /api/V3/courses/filter?category_id=&difficulty=
    public function filterCourses(Request $request)
    {
        $courses = $this->searchRepository->filterCourses(
            $request->input('category_id'),
            $request->input('difficulty')
        )->get();
        
        return response()->json($courses);
    }

    // GET /api/V3/mentors?search=
    public function searchMentors(Request $request)
    {
        $mentors = $this->searchRepository->searchMentors($request->input('search'));
        return response()->json($mentors);
    }

    // GET /api/V3/mentors/courses?search=
    // public function searchCourseByMentors(Request $request)
    // {
    //     $mentorsWithCourses = $this->searchRepository->searchMentorsWithCourses($request->input('search'));
    //     return response()->json($mentorsWithCourses);
    // }

    // GET /api/V3/students?badges=
    public function filterStudentsByBadge(Request $request)
    {
        $students = $this->searchRepository->filterStudentsByBadge($request->input('badges'));
        return response()->json($students);
    }
}