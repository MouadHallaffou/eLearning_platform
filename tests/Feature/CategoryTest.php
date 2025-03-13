<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

test("list categories", function () {
    Category::factory()->count(3)->create();

    $response = $this->getJson("api/category");

    $response->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                "*" => ['id', 'name', 'parent_id'],
            ],
        ]);
});

test("can show a category", function () {
    $category = Category::factory()->create();

    $response = $this->getJson("api/category/{$category->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $category->id,
            'name' => $category->name,
            'parent_id' => $category->parent_id,
        ]);
});

test("can create a category", function () {
    $categoryData = ['name' => 'New Category', 'parent_id' => null];

    $response = $this->postJson("api/category", $categoryData);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => $categoryData['name']]);

    $this->assertDatabaseHas('categories', ['name' => 'New Category']);
});

test("can update a category", function () {
    $category = Category::factory()->create();
    $updatedData = ['name' => 'Updated Category', 'parent_id' => null];

    $response = $this->putJson("api/category/{$category->id}", $updatedData);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Updated Category']);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Updated Category'
    ]);
});

test("can delete a category", function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson("api/category/{$category->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});
