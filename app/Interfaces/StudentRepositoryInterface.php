<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function getStudentCourses(int $studentId);
    public function getStudentProgress(int $studentId);
    public function getStudentBadges(int $studentId);
}