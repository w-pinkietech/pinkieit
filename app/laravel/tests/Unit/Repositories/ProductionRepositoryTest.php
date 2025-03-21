<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Production;
use App\Repositories\ProductionRepository;
use App\Enums\ProductionStatus;
use Illuminate\Foundation\Testing\WithFaker;

class ProductionRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProductionRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductionRepository();
    }

    public function test_can_create_production()
    {
        $data = [
            'line_id' => 1,
            'part_number_id' => 1,
            'status' => ProductionStatus::RUNNING,
            'started_at' => now(),
        ];

        $production = $this->repository->create($data);

        $this->assertInstanceOf(Production::class, $production);
        $this->assertEquals($data['line_id'], $production->line_id);
        $this->assertEquals($data['part_number_id'], $production->part_number_id);
        $this->assertEquals($data['status'], $production->status);
    }

    public function test_can_find_production_by_id()
    {
        $production = Production::factory()->create();

        $found = $this->repository->find($production->id);

        $this->assertInstanceOf(Production::class, $found);
        $this->assertEquals($production->id, $found->id);
    }

    public function test_can_update_production_status()
    {
        $production = Production::factory()->create([
            'status' => ProductionStatus::Running
        ]);

        $updated = $this->repository->update($production->id, [
            'status' => ProductionStatus::Stopped
        ]);

        $this->assertEquals(ProductionStatus::Stopped, $updated->status);
    }
}
