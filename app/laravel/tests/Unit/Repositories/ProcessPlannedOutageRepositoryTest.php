<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\ProcessPlannedOutage;
use App\Repositories\ProcessPlannedOutageRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProcessPlannedOutageRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProcessPlannedOutageRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProcessPlannedOutageRepository();
    }

    public function test_can_create_process_planned_outage()
    {
        $data = [
            'process_id' => 1,
            'planned_outage_id' => 1,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'active' => true,
        ];

        $outage = $this->repository->create($data);

        $this->assertInstanceOf(ProcessPlannedOutage::class, $outage);
        $this->assertEquals($data['process_id'], $outage->process_id);
        $this->assertEquals($data['planned_outage_id'], $outage->planned_outage_id);
        $this->assertEquals($data['start_time'], $outage->start_time);
        $this->assertEquals($data['end_time'], $outage->end_time);
        $this->assertEquals($data['active'], $outage->active);
    }

    public function test_can_find_process_planned_outage_by_id()
    {
        $outage = ProcessPlannedOutage::factory()->create();

        $found = $this->repository->find($outage->id);

        $this->assertInstanceOf(ProcessPlannedOutage::class, $found);
        $this->assertEquals($outage->id, $found->id);
    }

    public function test_can_update_process_planned_outage()
    {
        $outage = ProcessPlannedOutage::factory()->create([
            'active' => false
        ]);

        $updated = $this->repository->update($outage->id, [
            'active' => true
        ]);

        $this->assertTrue($updated->active);
    }

    public function test_can_get_active_outages_by_process()
    {
        $processId = 1;
        ProcessPlannedOutage::factory()->count(2)->create([
            'process_id' => $processId,
            'active' => true
        ]);
        ProcessPlannedOutage::factory()->create([
            'process_id' => $processId,
            'active' => false
        ]);

        $activeOutages = $this->repository->getActiveByProcessId($processId);

        $this->assertCount(2, $activeOutages);
        $this->assertTrue($activeOutages->every(fn($outage) => 
            $outage->process_id === $processId && $outage->active
        ));
    }
}
