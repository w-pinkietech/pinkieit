<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\ProductionLine;
use App\Repositories\ProductionLineRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProductionLineRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProductionLineRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductionLineRepository();
    }

    public function test_can_create_production_line()
    {
        $data = [
            'line_id' => 1,
            'production_id' => 1,
            'order' => 1,
            'status' => 'active',
        ];

        $productionLine = $this->repository->create($data);

        $this->assertInstanceOf(ProductionLine::class, $productionLine);
        $this->assertEquals($data['line_id'], $productionLine->line_id);
        $this->assertEquals($data['production_id'], $productionLine->production_id);
        $this->assertEquals($data['order'], $productionLine->order);
        $this->assertEquals($data['status'], $productionLine->status);
    }

    public function test_can_find_production_line_by_id()
    {
        $productionLine = ProductionLine::factory()->create();

        $found = $this->repository->find($productionLine->id);

        $this->assertInstanceOf(ProductionLine::class, $found);
        $this->assertEquals($productionLine->id, $found->id);
    }

    public function test_can_update_production_line()
    {
        $productionLine = ProductionLine::factory()->create([
            'status' => 'inactive'
        ]);

        $updated = $this->repository->update($productionLine->id, [
            'status' => 'active'
        ]);

        $this->assertEquals('active', $updated->status);
    }

    public function test_can_get_production_lines_by_production()
    {
        $productionId = 1;
        ProductionLine::factory()->count(2)->create([
            'production_id' => $productionId
        ]);
        ProductionLine::factory()->create(['production_id' => 2]); // Different production

        $found = $this->repository->getByProductionId($productionId);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($line) => $line->production_id === $productionId));
    }
}
