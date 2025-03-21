<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\CycleTime;
use App\Models\Process;
use App\Models\PartNumber;
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
        $process = Process::factory()->create();
        $partNumber = PartNumber::factory()->create();
        
        $data = [
            'process_id' => $process->process_id,
            'part_number_id' => $partNumber->part_number_id,
            'cycle_time' => 60.25,
            'over_time' => 120.5,
            'cycle_time_name' => 'Test Cycle Time',
        ];

        $cycleTime = new CycleTime($data);
        $this->repository->storeModel($cycleTime);

        $this->assertInstanceOf(CycleTime::class, $cycleTime);
        $this->assertEquals($data['process_id'], $cycleTime->process_id);
        $this->assertEquals($data['part_number_id'], $cycleTime->part_number_id);
        $this->assertEquals($data['cycle_time'], $cycleTime->cycle_time);
        $this->assertEquals($data['over_time'], $cycleTime->over_time);
        $this->assertEquals($data['cycle_time_name'], $cycleTime->cycle_time_name);
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
        $process = Process::factory()->create();
        $partNumber = PartNumber::factory()->create();
        
        $cycleTime = CycleTime::factory()->create([
            'process_id' => $process->process_id,
            'part_number_id' => $partNumber->part_number_id,
            'cycle_time' => 100
        ]);

        $updated = $this->repository->update($cycleTime->id, [
            'cycle_time' => 120
        ]);

        $this->assertEquals(120, $updated->cycle_time);
    }

    public function test_can_get_cycle_times_by_process_and_part()
    {
        $process = Process::factory()->create();
        $partNumber = PartNumber::factory()->create();
        
        CycleTime::factory()->count(3)->create([
            'process_id' => $process->process_id,
            'part_number_id' => $partNumber->part_number_id
        ]);
        CycleTime::factory()->create([
            'process_id' => Process::factory()->create()->process_id,
            'part_number_id' => $partNumber->part_number_id
        ]);

        $cycleTimes = $this->repository->getByProcessAndPart($process->process_id, $partNumber->part_number_id);

        $this->assertCount(3, $cycleTimes);
        $this->assertTrue($cycleTimes->every(fn($ct) => 
            $ct->process_id === $process->process_id && 
            $ct->part_number_id === $partNumber->part_number_id
        ));
    }
}
