<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function enrollUser($userId, $courseId)
    {
        return Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => 'pending',
        ]);
    }

    public function getEnrollmentsByCourse($courseId)
    {
        return Enrollment::with('user')->where('course_id', $courseId)->get();
    }

    public function updateEnrollmentStatus($enrollmentId, $status)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->update(['status' => $status]);
        return $enrollment;
    }

    public function deleteEnrollment($enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        $enrollment->delete();
        return $enrollment;
    }
}