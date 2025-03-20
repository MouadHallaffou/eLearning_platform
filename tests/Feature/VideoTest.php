<?php

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(DatabaseTransactions::class);

test("list videos", function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    Video::factory()->count(3)->create();

    $response = $this->actingAs($user)->getJson("api/videos");

    $response->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                "*" => ['id', 'title', 'url', 'course_id'],
            ],
        ]);
});

test("can show a video", function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $video = Video::factory()->create();

    $response = $this->actingAs($user)->getJson("api/videos/{$video->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $video->id,
            'title' => $video->title,
            'url' => $video->url,
            'course_id' => $video->course_id,
        ]);
});

test("can create a video", function () {
    $user = User::factory()->create();
    $user->assignRole('mentor');

    Storage::fake('public');
    $videoFile = UploadedFile::fake()->create('video.mp4', 1000);

    $videoData = [
        'title' => 'New Video',
        'description' => 'This is a new video.',
        'course_id' => 1,
        'video_file' => $videoFile,
    ];

    $response = $this->actingAs($user)->postJson("api/videos", $videoData);

    $response->assertStatus(201)
        ->assertJsonFragment(['title' => 'New Video']);

    $this->assertDatabaseHas('videos', ['title' => 'New Video']);
});

test("can update a video", function () {
    $user = User::factory()->create();
    $user->assignRole('mentor');

    $video = Video::factory()->create();

    Storage::fake('public');
    $updatedVideoFile = UploadedFile::fake()->create('updated_video.mp4', 1000);

    $updatedData = [
        'title' => 'Updated Video Title',
        'description' => 'This is an updated video.',
        'video_file' => $updatedVideoFile,
    ];

    $response = $this->actingAs($user)->putJson("api/videos/{$video->id}", $updatedData);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Video Title']);

    $this->assertDatabaseHas('videos', [
        'id' => $video->id,
        'title' => 'Updated Video Title'
    ]);
});

test("can delete a video", function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $video = Video::factory()->create();

    $response = $this->actingAs($user)->deleteJson("api/videos/{$video->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('videos', ['id' => $video->id]);
});

test("unauthorized access for creating a video", function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $response = $this->actingAs($user)->postJson("api/videos", []);

    $response->assertStatus(403)
        ->assertJson(['message' => 'Unauthorized']);
});

test("unauthorized access for deleting a video", function () {
    $user = User::factory()->create();
    $user->assignRole('student');

    $video = Video::factory()->create();

    $response = $this->actingAs($user)->deleteJson("api/videos/{$video->id}");

    $response->assertStatus(403)
        ->assertJson(['message' => 'Unauthorized']);
});