<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use Tests\TestCase\TestFormRequest;
use App\Models\Production;
use App\Models\ProductionLine;
use App\Repositories\ProductionRepository;
use App\Enums\ProductionStatus;
use App\Data\PayloadData;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

class ProductionRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProductionRepository $repository;
    protected $model = Production::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductionRepository();
    }

    public function test_can_save_production()
    {
        $productionLine = ProductionLine::factory()->create();
        $now = Carbon::now();
        
        $payloadData = new PayloadData([
            'at' => $now,
            'count' => 10,
            'defectiveCount' => 0,
            'status' => ProductionStatus::RUNNING,
            'inPlannedOutage' => false,
            'workingTime' => 3600,
            'loadingTime' => 3600,
            'operatingTime' => 3600,
            'netTime' => 3600,
            'breakdowns' => [],
            'autoResumeCount' => 0,
        ]);

        $production = $this->repository->save($productionLine->production_line_id, $payloadData);

        $this->assertInstanceOf(Production::class, $production);
        $this->assertEquals($productionLine->production_line_id, $production->production_line_id);
        $this->assertEquals($payloadData->count, $production->count);
        $this->assertEquals($payloadData->defectiveCount(), $production->defective_count);
        $this->assertEquals($payloadData->status(), $production->status);
        $this->assertEquals($payloadData->inPlannedOutage(), $production->in_planned_outage);
    }

    public function test_can_find_production_by_id()
    {
        $production = Production::factory()->create();

        $found = $this->repository->find($production->id);

        $this->assertInstanceOf(Production::class, $found);
        $this->assertEquals($production->id, $found->id);
    }

    public function test_can_judge_breakdown()
    {
        $production = Production::factory()->create([
            'production_line_id' => 1,
            'count' => 10,
            'status' => ProductionStatus::RUNNING
        ]);

        $breakdownTime = now()->addMinutes(5);
        
        // No subsequent production with higher count or different status
        $result = $this->repository->judgeBreakdown($production, $breakdownTime);
        $this->assertTrue($result);

        // Create subsequent production with higher count
        Production::factory()->create([
            'production_line_id' => $production->production_line_id,
            'count' => 11,
            'at' => $breakdownTime->subMinute()
        ]);

        $result = $this->repository->judgeBreakdown($production, $breakdownTime);
        $this->assertFalse($result);
    }
}
