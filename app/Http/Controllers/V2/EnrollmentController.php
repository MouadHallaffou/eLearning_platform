<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateEnrollmentRequest;

class EnrollmentController extends Controller
{
    protected $enrollmentRepository;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    // POST /api/V1/courses/{id}/enroll
    public function enroll(Request $request, $courseId)
    {
        try {
            $user = $request->user();
            $enrollment = $this->enrollmentRepository->enrollUser($user->id, $courseId);
            return response()->json(['message' => 'Enrollment successful.', 'data' => $enrollment], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // GET /api/V1/courses/{id}/enrollments
    public function listEnrollments($courseId)
    {
        try {
            $enrollments = $this->enrollmentRepository->getEnrollmentsByCourse($courseId);
            return response()->json(['data' => $enrollments]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT /api/V2/enrollments/{id}
    public function updateEnrollment(UpdateEnrollmentRequest $request, $enrollmentId)
    {
        try {
            $enrollment = $this->enrollmentRepository->updateEnrollmentStatus($enrollmentId, $request->status);
            return response()->json(['message' => 'Enrollment updated.', 'data' => $enrollment]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/V2/enrollments/{id}
    public function deleteEnrollment(Request $request, $enrollmentId)
    {
        try {
            $this->enrollmentRepository->deleteEnrollment($enrollmentId);
            return response()->json(['message' => 'Enrollment deleted.'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
        }
    }
}