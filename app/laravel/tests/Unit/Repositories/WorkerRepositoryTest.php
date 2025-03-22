<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Worker;
use App\Repositories\WorkerRepository;
use Illuminate\Foundation\Testing\WithFaker;

class WorkerRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private WorkerRepository $repository;
    private $model = Worker::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new WorkerRepository();
    }

    public function test_can_create_worker()
    {
        $data = [
            'name' => 'John Doe',
            'employee_id' => 'EMP-001',
            'line_id' => 1,
        ];

        $worker = new $this->model($data);
        $this->repository->storeModel($worker);

        $this->assertInstanceOf(Worker::class, $worker);
        $this->assertEquals($data['name'], $worker->name);
        $this->assertEquals($data['employee_id'], $worker->employee_id);
        $this->assertEquals($data['line_id'], $worker->line_id);
    }

    public function test_can_find_worker_by_id()
    {
        $worker = Worker::factory()->create();

        $found = $this->repository->find($worker->id);

        $this->assertInstanceOf(Worker::class, $found);
        $this->assertEquals($worker->id, $found->id);
    }

    public function test_can_update_worker()
    {
        $worker = Worker::factory()->create([
            'name' => 'Old Name'
        ]);

        $updated = $this->repository->update($worker->id, [
            'name' => 'New Name'
        ]);

        $this->assertEquals('New Name', $updated->name);
    }

    public function test_can_get_workers_by_line()
    {
        $lineId = 1;
        $workers = Worker::factory()->count(3)->create([
            'line_id' => $lineId
        ]);
        Worker::factory()->create(['line_id' => 2]); // Different line

        $foundWorkers = $this->repository->getByLineId($lineId);

        $this->assertCount(3, $foundWorkers);
        $this->assertTrue($foundWorkers->every(fn($worker) => $worker->line_id === $lineId));
    }
}
