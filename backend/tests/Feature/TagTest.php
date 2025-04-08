<?php

use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test("list tags", function () {
    Tag::factory()->count(3)->create();

    $response = $this->get("api/tag");

    $response->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                "*" => ['id', 'name'],
            ],
        ]);
});

test("can show a tag", function () {
    $tag = Tag::factory()->create();

    $response = $this->get("api/tag/{$tag->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $tag->id, 'name' => $tag->name]);
});

test("can create a tag", function () {
    $tagData = ['name' => 'New Tag'];

    $response = $this->postJson("api/tag", ['tags' => [$tagData['name']]]);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => $tagData['name']]);

    $this->assertDatabaseHas('tags', $tagData);
});

test("can update a tag", function () {
    $tag = Tag::factory()->create();
    $updatedData = ['name' => 'Updated Tag'];

    $response = $this->putJson("api/tag/{$tag->id}", $updatedData);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => $updatedData['name']]);

    $this->assertDatabaseHas('tags', $updatedData);
});

test("can delete a tag", function () {
    $tag = Tag::factory()->create();

    $response = $this->delete("api/tag/{$tag->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});
