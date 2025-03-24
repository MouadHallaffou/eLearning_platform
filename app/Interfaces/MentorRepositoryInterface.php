<?php

namespace App\Interfaces;

interface MentorRepositoryInterface
{
    public function getMentorCourses(int $mentorId);
    public function getMentorStudents(int $mentorId);
    public function getMentorPerformance(int $mentorId);
}