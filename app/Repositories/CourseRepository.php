<?php

namespace App\Repositories;

use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
   /**
    * Create a new class instance.
    */
   public function __construct()
   {
      //
   }

   public function index()
   {
      return Course::leftJoin("categories", "courses.category_id", "=", "categories.id")->select("courses.*", "categories.name As categoryName")->get();
   }

   public function getById($id)
   {
      return Course::findOrFail($id);
   }

   public function store(array $data)
   {
      return Course::create($data);
   }

   public function update(array $details, $id)
   {
      $course = Course::find($id);
      if ($course) {
         $course->update($details);
      }

      return $course;
   }

   public function delete($id)
   {
      Course::destroy($id);
   }
}
