<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Video;
use App\Models\Course;
use App\Models\Enrollment;
use App\Interfaces\MentorRepositoryInterface;

class MentorRepository implements MentorRepositoryInterface
{
    public function getMentorCourses(int $mentorId)
    {
        return Course::where('user_id', $mentorId) 
            ->with(['category', 'videos']) 
            ->withCount('videos as content_count') 
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'category' => $course->category->name,
                    'content_count' => $course->content_count,
                    'students_count' => $course->enrollments()->count()
                ];
            });
    }

    public function getMentorStudents(int $mentorId)
    {
        return User::whereHas('enrollments.course', function ($query) use ($mentorId) {
            $query->where('user_id', $mentorId); 
        })
            ->withCount('enrollments')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'enrollments_count' => $student->enrollments_count
                ];
            });
    }

    public function getMentorPerformance(int $mentorId)
    {
        return [
            'courses_count' => Course::where('user_id', $mentorId)->count(),
            'students_count' => Enrollment::whereHas(
                'course',
                fn($q) => $q->where('user_id', $mentorId)
            )->distinct('user_id')->count(),

            'active_courses' => Course::where('user_id', $mentorId)
                ->has('enrollments')
                ->count(),

            'videos_count' => Video::whereHas(
                'course',
                fn($q) => $q->where('user_id', $mentorId)
            )->count()
        ];
    }
}
