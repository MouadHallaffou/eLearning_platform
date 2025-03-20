<?php

use App\Models\Tag;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test("list courses", function () {
    Course::factory()->count(3)->create();

    $response = $this->getJson('api/courses');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'description',
                'content',
                'cover',
                'duration',
                'level',
                'category_id',
                'user_id'
            ],
        ],
    ]);
});

test("can show a course", function () {
    $course = Course::factory()->create();
    $response = $this->getJson("api/courses/{$course->id}");
    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => $course->id,
        'title' => $course->title,
        'description' => $course->description,
    ]);
});

test("mentor can create a course", function () {
    $mentor = User::factory()->create();
    $mentor->assignRole('mentor');

    $courseData = [
        'title' => 'New Course',
        'description' => 'This is a new course.',
        'content' => 'Course content',
        'cover' => 'cover.jpg',
        'duration' => 60,
        'level' => 'beginner',
        'category_id' => 1,
        'user_id' => Auth::id(),
    ];

    $response = $this->actingAs($mentor)->postJson("api/courses", $courseData);

    $response->assertStatus(201)
        ->assertJsonFragment(['title' => 'New Course']);

    $this->assertDatabaseHas('courses', [
        'title' => 'New Course',
        'user_id' => $mentor->id,
    ]);
});

test("can update a course", function () {
    $course = Course::factory()->create();
    $tag = Tag::factory()->create();
    $updatedData = [
        'title' => 'Updated Course Title',
        'description' => 'Updated Course Description',
        'content' => 'Updated Course Content',
        'cover' => 'http://example.com/updated-cover.jpg',
        'duration' => 150,
        'level' => 'intermediate',
        'category_id' => 2,
        'tag_ids' => [$tag->id],
    ];
    $response = $this->putJson("api/courses/{$course->id}", $updatedData);
    $response->assertStatus(200);
    $response->assertJsonFragment([
        'title' => $updatedData['title'],
        'description' => $updatedData['description'],
    ]);

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => $updatedData['title'],
        'description' => $updatedData['description'],
    ]);

    $course->refresh();
    $this->assertCount(1, $course->tags);
});

test("can delete a course", function () {
    $course = Course::factory()->create();
    $response = $this->deleteJson("api/courses/{$course->id}");
    $response->assertStatus(204);
    $this->assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});


