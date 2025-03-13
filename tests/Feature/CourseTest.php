<?php

use App\Models\Course;
use App\Models\Tag;
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
                'video',
                'cover',
                'duration',
                'level',
                'category_id',
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

test("can create a course", function () {
    $tag = Tag::factory()->create();
    $courseData = [
        'title' => 'New Course',
        'description' => 'Course Description',
        'content' => 'Course Content',
        'video' => 'http://example.com/video.mp4',
        'cover' => 'http://example.com/cover.jpg',
        'duration' => 120,
        'level' => 'beginner',
        'category_id' => 1,
        'tag_ids' => [$tag->id],
    ];

    $response = $this->postJson('api/courses', $courseData);
    $response->assertStatus(201);
    $response->assertJsonFragment([
        'title' => $courseData['title'],
        'description' => $courseData['description'],
    ]);

    $this->assertDatabaseHas('courses', [
        'title' => $courseData['title'],
        'description' => $courseData['description'],
    ]);
    $course = Course::latest()->first();
    $this->assertCount(1, $course->tags);
});

test("can update a course", function () {
    $course = Course::factory()->create();
    $tag = Tag::factory()->create();
    $updatedData = [
        'title' => 'Updated Course Title',
        'description' => 'Updated Course Description',
        'content' => 'Updated Course Content',
        'video' => 'http://example.com/updated-video.mp4',
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


