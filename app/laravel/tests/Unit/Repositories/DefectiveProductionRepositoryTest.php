<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\DefectiveProduction;
use App\Repositories\DefectiveProductionRepository;
use Illuminate\Foundation\Testing\WithFaker;

class DefectiveProductionRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private DefectiveProductionRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DefectiveProductionRepository();
    }

    public function test_can_create_defective_production()
    {
        $data = [
            'production_id' => 1,
            'process_id' => 1,
            'quantity' => 5,
            'reason' => 'Quality issue',
            'recorded_at' => now(),
        ];

        $defectiveProduction = new DefectiveProduction($data);
        $this->repository->storeModel($defectiveProduction);

        $this->assertInstanceOf(DefectiveProduction::class, $defectiveProduction);
        $this->assertEquals($data['production_id'], $defectiveProduction->production_id);
        $this->assertEquals($data['process_id'], $defectiveProduction->process_id);
        $this->assertEquals($data['quantity'], $defectiveProduction->quantity);
        $this->assertEquals($data['reason'], $defectiveProduction->reason);
        $this->assertEquals($data['recorded_at']->timestamp, $defectiveProduction->recorded_at->timestamp);
    }

    public function test_can_find_defective_production_by_id()
    {
        $defectiveProduction = DefectiveProduction::factory()->create();

        $found = $this->repository->find($defectiveProduction->id);

        $this->assertInstanceOf(DefectiveProduction::class, $found);
        $this->assertEquals($defectiveProduction->id, $found->id);
    }

    public function test_can_get_defective_productions_by_production()
    {
        $productionId = 1;
        $defectiveProductions = DefectiveProduction::factory()->count(3)->create([
            'production_id' => $productionId
        ]);
        DefectiveProduction::factory()->create(['production_id' => 2]); // Different production

        $found = $this->repository->getByProductionId($productionId);

        $this->assertCount(3, $found);
        $this->assertTrue($found->every(fn($dp) => $dp->production_id === $productionId));
    }

    public function test_can_get_defective_productions_by_date_range()
    {
        $startDate = now()->subDays(2);
        $endDate = now();

        DefectiveProduction::factory()->create([
            'recorded_at' => now()->subDays(3)
        ]); // Outside range
        DefectiveProduction::factory()->count(2)->create([
            'recorded_at' => now()->subDay()
        ]); // Inside range

        $found = $this->repository->getByDateRange($startDate, $endDate);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($dp) => 
            $dp->recorded_at->greaterThanOrEqualTo($startDate) &&
            $dp->recorded_at->lessThanOrEqualTo($endDate)
        ));
    }
}
