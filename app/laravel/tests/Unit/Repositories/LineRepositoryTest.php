<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Line;
use App\Repositories\LineRepository;
use Illuminate\Foundation\Testing\WithFaker;

class LineRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private LineRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new LineRepository();
    }

    public function test_can_create_line()
    {
        $data = [
            'name' => 'Production Line A',
            'order' => 1,
        ];

        $line = $this->repository->create($data);

        $this->assertInstanceOf(Line::class, $line);
        $this->assertEquals($data['name'], $line->name);
        $this->assertEquals($data['order'], $line->order);
    }

    public function test_can_find_line_by_id()
    {
        $line = Line::factory()->create();

        $found = $this->repository->find($line->id);

        $this->assertInstanceOf(Line::class, $found);
        $this->assertEquals($line->id, $found->id);
    }

    public function test_can_update_line()
    {
        $line = Line::factory()->create([
            'name' => 'Old Line Name'
        ]);

        $updated = $this->repository->update($line->id, [
            'name' => 'New Line Name'
        ]);

        $this->assertEquals('New Line Name', $updated->name);
    }

    public function test_can_get_ordered_lines()
    {
        Line::factory()->create(['order' => 3, 'name' => 'Line C']);
        Line::factory()->create(['order' => 1, 'name' => 'Line A']);
        Line::factory()->create(['order' => 2, 'name' => 'Line B']);

        $lines = $this->repository->getOrdered();

        $this->assertEquals(['Line A', 'Line B', 'Line C'], $lines->pluck('name')->toArray());
    }
}
