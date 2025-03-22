<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Producer;
use App\Models\Worker;
use App\Models\ProductionLine;
use App\Repositories\ProducerRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProducerRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProducerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProducerRepository();
    }

    public function test_can_create_producer()
    {
        $worker = Worker::factory()->create();
        $productionLine = ProductionLine::factory()->create();
        
        $data = [
            'worker_id' => $worker->worker_id,
            'production_line_id' => $productionLine->production_line_id,
            'identification_number' => 'PROD-001',
            'worker_name' => 'John Doe',
            'start' => now(),
        ];

        $producer = new Producer($data);
        $this->repository->storeModel($producer);

        $this->assertInstanceOf(Producer::class, $producer);
        $this->assertEquals($data['worker_id'], $producer->worker_id);
        $this->assertEquals($data['production_line_id'], $producer->production_line_id);
        $this->assertEquals($data['identification_number'], $producer->identification_number);
        $this->assertEquals($data['worker_name'], $producer->worker_name);
        $this->assertEquals($data['start']->timestamp, $producer->start->timestamp);
    }

    public function test_can_find_producer_by_id()
    {
        $producer = Producer::factory()->create();

        $found = $this->repository->find($producer->id);

        $this->assertInstanceOf(Producer::class, $found);
        $this->assertEquals($producer->id, $found->id);
    }

    public function test_can_update_producer()
    {
        $worker = Worker::factory()->create();
        $productionLine = ProductionLine::factory()->create();
        
        $producer = Producer::factory()->create([
            'worker_id' => $worker->worker_id,
            'production_line_id' => $productionLine->production_line_id,
            'stop' => null
        ]);

        $updated = $this->repository->update($producer->id, [
            'stop' => now()
        ]);

        $this->assertNotNull($updated->stop);
    }

    public function test_can_get_active_producers_by_production_line()
    {
        $productionLine = ProductionLine::factory()->create();
        $worker = Worker::factory()->create();
        
        Producer::factory()->count(2)->create([
            'production_line_id' => $productionLine->production_line_id,
            'worker_id' => $worker->worker_id,
            'stop' => null
        ]);
        Producer::factory()->create([
            'production_line_id' => $productionLine->production_line_id,
            'worker_id' => $worker->worker_id,
            'stop' => now()
        ]);

        $activeProducers = $this->repository->getActiveByProductionLineId($productionLine->production_line_id);

        $this->assertCount(2, $activeProducers);
        $this->assertTrue($activeProducers->every(fn($producer) => 
            $producer->production_line_id === $productionLine->production_line_id && 
            $producer->stop === null
        ));
    }
}
