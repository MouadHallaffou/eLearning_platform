<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Enrollment;
use App\Models\Badge;

class StudentRepository implements StudentRepositoryInterface
{
    public function getStudentCourses(int $studentId)
    {
        return Enrollment::where('user_id', $studentId)
                       ->with('course')
                       ->get()
                       ->pluck('course');
    }

    public function getStudentProgress(int $studentId)
    {
        $enrollments = Enrollment::where('user_id', $studentId)
                               ->with(['course.lessons', 'completions'])
                               ->get();

        return $enrollments->map(function ($enrollment) {
            $total = $enrollment->course->lessons->count();
            $completed = $enrollment->completions->count();
            
            return [
                'course_id' => $enrollment->course_id,
                'progress' => $total ? round(($completed / $total) * 100, 2) : 0
            ];
        });
    }

    public function getStudentBadges(int $studentId)
    {
        // return Badge::where('user_id', $studentId)
        //           ->with('badgeType')
        //           ->get();
    }
}