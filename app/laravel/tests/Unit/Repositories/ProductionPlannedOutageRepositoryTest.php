<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\ProductionPlannedOutage;
use App\Models\ProductionHistory;
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
        $productionHistory = ProductionHistory::factory()->create();
        
        $data = [
            'production_history_id' => $productionHistory->production_history_id,
            'planned_outage_name' => 'Maintenance Break',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ];

        $outage = new ProductionPlannedOutage($data);
        $this->repository->storeModel($outage);

        $this->assertInstanceOf(ProductionPlannedOutage::class, $outage);
        $this->assertEquals($data['production_history_id'], $outage->production_history_id);
        $this->assertEquals($data['planned_outage_name'], $outage->planned_outage_name);
        $this->assertEquals($data['start_time'], $outage->start_time);
        $this->assertEquals($data['end_time'], $outage->end_time);
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
            'start_time' => '09:00:00'
        ]);

        $updated = $this->repository->update($outage->id, [
            'start_time' => '10:00:00'
        ]);

        $this->assertEquals('10:00:00', $updated->start_time);
    }

    public function test_can_get_outages_by_production_history()
    {
        $productionHistory = ProductionHistory::factory()->create();
        $productionHistoryId = $productionHistory->production_history_id;
        ProductionPlannedOutage::factory()->count(2)->create([
            'production_history_id' => $productionHistoryId,
            'start_time' => '09:00:00',
            'end_time' => '10:00:00'
        ]);
        ProductionPlannedOutage::factory()->create([
            'production_history_id' => 2,
            'start_time' => '11:00:00',
            'end_time' => '12:00:00'
        ]);

        $outages = $this->repository->getByProductionHistoryId($productionHistoryId);

        $this->assertCount(2, $outages);
        $this->assertTrue($outages->every(fn($outage) => 
            $outage->production_history_id === $productionHistoryId
        ));
    }
}
