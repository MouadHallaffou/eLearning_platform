<?php

namespace App\Interfaces;

interface EnrollmentRepositoryInterface
{
    public function enrollUser($userId, $courseId);
    public function getEnrollmentsByCourse($courseId);
    public function updateEnrollmentStatus($enrollmentId, $status);
    public function deleteEnrollment($enrollmentId);
    public function getEnrollmentByUserAndCourse($userId, $courseId); 
}

