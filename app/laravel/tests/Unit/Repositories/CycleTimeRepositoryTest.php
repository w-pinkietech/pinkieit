<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\CycleTime;
use App\Repositories\CycleTimeRepository;
use Illuminate\Foundation\Testing\WithFaker;

class CycleTimeRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private CycleTimeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CycleTimeRepository();
    }

    public function test_can_create_cycle_time()
    {
        $data = [
            'process_id' => 1,
            'part_number_id' => 1,
            'seconds' => 120,
            'target_seconds' => 100,
        ];

        $cycleTime = new CycleTime($data);
        $this->repository->storeModel($cycleTime);

        $this->assertInstanceOf(CycleTime::class, $cycleTime);
        $this->assertEquals($data['process_id'], $cycleTime->process_id);
        $this->assertEquals($data['part_number_id'], $cycleTime->part_number_id);
        $this->assertEquals($data['seconds'], $cycleTime->seconds);
        $this->assertEquals($data['target_seconds'], $cycleTime->target_seconds);
    }

    public function test_can_find_cycle_time_by_id()
    {
        $cycleTime = CycleTime::factory()->create();

        $found = $this->repository->find($cycleTime->id);

        $this->assertInstanceOf(CycleTime::class, $found);
        $this->assertEquals($cycleTime->id, $found->id);
    }

    public function test_can_update_cycle_time()
    {
        $cycleTime = CycleTime::factory()->create([
            'seconds' => 100
        ]);

        $updated = $this->repository->update($cycleTime->id, [
            'seconds' => 120
        ]);

        $this->assertEquals(120, $updated->seconds);
    }

    public function test_can_get_cycle_times_by_process_and_part()
    {
        $processId = 1;
        $partNumberId = 1;
        
        CycleTime::factory()->count(3)->create([
            'process_id' => $processId,
            'part_number_id' => $partNumberId
        ]);
        CycleTime::factory()->create([
            'process_id' => 2,
            'part_number_id' => $partNumberId
        ]);

        $cycleTimes = $this->repository->getByProcessAndPart($processId, $partNumberId);

        $this->assertCount(3, $cycleTimes);
        $this->assertTrue($cycleTimes->every(fn($ct) => 
            $ct->process_id === $processId && 
            $ct->part_number_id === $partNumberId
        ));
    }
}
