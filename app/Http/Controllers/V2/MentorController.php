<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Interfaces\MentorRepositoryInterface;
use Illuminate\Http\JsonResponse;

class MentorController extends Controller
{
    public function __construct(
        private MentorRepositoryInterface $mentorRepository
    ) {}

    ///api/V1/mentors/{id}/courses
    public function getCourses(int $id): JsonResponse
    {
        return response()->json($this->mentorRepository->getMentorCourses($id));
    }
    ///api/V1/mentors/{id}/students
    public function getStudents(int $id): JsonResponse
    {
        return response()->json($this->mentorRepository->getMentorStudents($id));
    }
    //api/V1/mentors/{id}/performance
    public function getPerformance(int $id): JsonResponse
    {
        return response()->json($this->mentorRepository->getMentorPerformance($id));
    }
}
