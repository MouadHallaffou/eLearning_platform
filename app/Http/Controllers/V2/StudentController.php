<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository
    ) {}

    public function getCourses(int $id): JsonResponse
    {
        return response()->json($this->studentRepository->getStudentCourses($id));
    }

    public function getProgress(int $id): JsonResponse
    {
        return response()->json($this->studentRepository->getStudentProgress($id));
    }

    public function getBadges(int $id): JsonResponse
    {
        return response()->json($this->studentRepository->getStudentBadges($id));
    }
}