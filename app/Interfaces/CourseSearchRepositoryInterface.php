<?php

namespace App\Interfaces;

interface CourseSearchRepositoryInterface
{
    public function searchCourses($query);
    public function filterCourses($categoryId, $difficulty);
    public function searchMentors($query);
    public function filterStudentsByBadge($badgeId);
}