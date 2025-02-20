<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\ProductionPlannedOutage;
use App\Repositories\ProductionPlannedOutageRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProductionPlannedOutageRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProductionPlannedOutageRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductionPlannedOutageRepository();
    }

    public function test_can_create_production_planned_outage()
    {
        $data = [
            'production_id' => 1,
            'planned_outage_id' => 1,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'active' => true,
        ];

        $outage = $this->repository->create($data);

        $this->assertInstanceOf(ProductionPlannedOutage::class, $outage);
        $this->assertEquals($data['production_id'], $outage->production_id);
        $this->assertEquals($data['planned_outage_id'], $outage->planned_outage_id);
        $this->assertEquals($data['start_time'], $outage->start_time);
        $this->assertEquals($data['end_time'], $outage->end_time);
        $this->assertEquals($data['active'], $outage->active);
    }

    public function test_can_find_production_planned_outage_by_id()
    {
        $outage = ProductionPlannedOutage::factory()->create();

        $found = $this->repository->find($outage->id);

        $this->assertInstanceOf(ProductionPlannedOutage::class, $found);
        $this->assertEquals($outage->id, $found->id);
    }

    public function test_can_update_production_planned_outage()
    {
        $outage = ProductionPlannedOutage::factory()->create([
            'active' => false
        ]);

        $updated = $this->repository->update($outage->id, [
            'active' => true
        ]);

        $this->assertTrue($updated->active);
    }

    public function test_can_get_active_outages_by_production()
    {
        $productionId = 1;
        ProductionPlannedOutage::factory()->count(2)->create([
            'production_id' => $productionId,
            'active' => true
        ]);
        ProductionPlannedOutage::factory()->create([
            'production_id' => $productionId,
            'active' => false
        ]);

        $activeOutages = $this->repository->getActiveByProductionId($productionId);

        $this->assertCount(2, $activeOutages);
        $this->assertTrue($activeOutages->every(fn($outage) => 
            $outage->production_id === $productionId && $outage->active
        ));
    }
}
