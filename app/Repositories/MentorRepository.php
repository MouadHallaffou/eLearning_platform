<?php

namespace App\Repositories;

use App\Interfaces\MentorRepositoryInterface;
use App\Models\Course;
use App\Models\User;

class MentorRepository implements MentorRepositoryInterface
{
    public function getMentorCourses(int $mentorId)
    {
        return Course::where('user_id', $mentorId)
            ->withCount(['courses' => function ($query) {
                $query->whereNull('deleted_at');
            }])
            ->with('courses')
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'lesson_count' => $course->courses_count,
                    'total_duration' => $course->courses->sum('duration')
                ];
            });
    }

    public function getMentorStudents(int $mentorId)
    {
        return User::whereHas('enrollments.course', function ($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId);
        })
            ->withCount('enrollments')
            ->get();
    }

    public function getMentorPerformance(int $mentorId)
    {
        return [
            'courses_count' => Course::where('mentor_id', $mentorId)->count(),
            'students_count' => $this->getMentorStudents($mentorId)->count(),
        ];
    }
}
