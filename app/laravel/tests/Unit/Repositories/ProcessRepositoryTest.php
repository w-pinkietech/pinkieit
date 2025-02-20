<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Process;
use App\Repositories\ProcessRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProcessRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProcessRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProcessRepository();
    }

    public function test_can_create_process()
    {
        $data = [
            'line_id' => 1,
            'name' => 'Assembly Process',
            'order' => 1,
            'production_history_id' => 1,
        ];

        $process = $this->repository->create($data);

        $this->assertInstanceOf(Process::class, $process);
        $this->assertEquals($data['line_id'], $process->line_id);
        $this->assertEquals($data['name'], $process->name);
        $this->assertEquals($data['order'], $process->order);
    }

    public function test_can_find_process_by_id()
    {
        $process = Process::factory()->create();

        $found = $this->repository->find($process->id);

        $this->assertInstanceOf(Process::class, $found);
        $this->assertEquals($process->id, $found->id);
    }

    public function test_can_update_process()
    {
        $process = Process::factory()->create([
            'name' => 'Old Process Name'
        ]);

        $updated = $this->repository->update($process->id, [
            'name' => 'New Process Name'
        ]);

        $this->assertEquals('New Process Name', $updated->name);
    }

    public function test_can_get_processes_by_line_id()
    {
        $lineId = 1;
        $processes = Process::factory()->count(3)->create([
            'line_id' => $lineId
        ]);

        $found = $this->repository->getByLineId($lineId);

        $this->assertCount(3, $found);
        $this->assertEquals($processes->pluck('id')->sort()->values(), $found->pluck('id')->sort()->values());
    }
}
