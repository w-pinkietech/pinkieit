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
            'line_name' => 'Production Line A',
            'process_id' => 1,
            'raspberry_pi_id' => 1,
            'worker_id' => 1,
            'chart_color' => '#FF0000',
            'pin_number' => 1,
        ];

        $line = new Line($data);
        $this->repository->storeModel($line);

        $this->assertInstanceOf(Line::class, $line);
        $this->assertEquals($data['line_name'], $line->line_name);
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
            'line_name' => 'Old Line Name'
        ]);

        $updated = $this->repository->update($line->id, [
            'line_name' => 'New Line Name'
        ]);

        $this->assertEquals('New Line Name', $updated->name);
    }

    public function test_can_get_ordered_lines()
    {
        Line::factory()->create(['order' => 3, 'line_name' => 'Line C']);
        Line::factory()->create(['order' => 1, 'line_name' => 'Line A']);
        Line::factory()->create(['order' => 2, 'line_name' => 'Line B']);

        $lines = $this->repository->getOrdered();

        $this->assertEquals(['Line A', 'Line B', 'Line C'], $lines->pluck('line_name')->toArray());
    }
}
