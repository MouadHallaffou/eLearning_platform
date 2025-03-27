<?php

namespace Tests\Feature;

use App\Models\Badge;
use App\Repositories\BadgeRepository;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions; 

class BadgeRepositoryTest extends TestCase
{
    use DatabaseTransactions; 

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BadgeRepository();
    }

    /** @test */
    public function it_can_create_a_badge()
    {
        $data = [
            'name' => 'Test Badge',
            'description' => 'Test Description',
            'type' => 'student',
        ];

        $badge = $this->repository->create($data);

        $this->assertInstanceOf(Badge::class, $badge);
        $this->assertEquals($data['name'], $badge->name);
        $this->assertDatabaseHas('badges', $data);
    }

    /** @test */
    public function it_can_find_a_badge()
    {
        $badge = Badge::factory()->create();
        $found = $this->repository->find($badge->id);

        $this->assertInstanceOf(Badge::class, $found);
        $this->assertEquals($badge->id, $found->id);
    }

}