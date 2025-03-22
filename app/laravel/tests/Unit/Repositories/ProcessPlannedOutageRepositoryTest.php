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
    protected $model = ProcessPlannedOutage::class;

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
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ];

        $outage = new $this->model($data);
        $this->repository->storeModel($outage);

        $this->assertInstanceOf(ProcessPlannedOutage::class, $outage);
        $this->assertEquals($data['process_id'], $outage->process_id);
        $this->assertEquals($data['planned_outage_id'], $outage->planned_outage_id);
        $this->assertEquals($data['start_time'], $outage->start_time);
        $this->assertEquals($data['end_time'], $outage->end_time);
        $this->assertEquals($data['start_time']->timestamp, $outage->start_time->timestamp);
        $this->assertEquals($data['end_time']->timestamp, $outage->end_time->timestamp);
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
            'start_time' => now()->subHour(),
            'end_time' => now(),
        ]);

        $newEndTime = now()->addHours(2);
        $updated = $this->repository->update($outage->id, [
            'end_time' => $newEndTime
        ]);

        $this->assertEquals($newEndTime->timestamp, $updated->end_time->timestamp);
    }

    public function test_can_get_active_outages_by_process()
    {
        $processId = 1;
        ProcessPlannedOutage::factory()->count(2)->create([
            'process_id' => $processId,
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour()
        ]);
        ProcessPlannedOutage::factory()->create([
            'process_id' => $processId,
            'start_time' => now()->addDays(2),
            'end_time' => now()->addDays(3)
        ]);

        $currentOutages = $this->repository->getCurrentOutagesByProcessId($processId);

        $this->assertCount(2, $currentOutages);
        $this->assertTrue($currentOutages->every(fn($outage) => 
            $outage->process_id === $processId && 
            $outage->start_time <= now() && 
            $outage->end_time >= now()
        ));
    }
}
