<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Producer;
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
        $data = [
            'process_id' => 1,
            'name' => 'Production Unit A',
            'status' => 'active',
            'capacity' => 100,
        ];

        $producer = $this->repository->create($data);

        $this->assertInstanceOf(Producer::class, $producer);
        $this->assertEquals($data['process_id'], $producer->process_id);
        $this->assertEquals($data['name'], $producer->name);
        $this->assertEquals($data['status'], $producer->status);
        $this->assertEquals($data['capacity'], $producer->capacity);
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
        $producer = Producer::factory()->create([
            'status' => 'inactive'
        ]);

        $updated = $this->repository->update($producer->id, [
            'status' => 'active'
        ]);

        $this->assertEquals('active', $updated->status);
    }

    public function test_can_get_active_producers_by_process()
    {
        $processId = 1;
        Producer::factory()->count(2)->create([
            'process_id' => $processId,
            'status' => 'active'
        ]);
        Producer::factory()->create([
            'process_id' => $processId,
            'status' => 'inactive'
        ]);

        $activeProducers = $this->repository->getActiveByProcessId($processId);

        $this->assertCount(2, $activeProducers);
        $this->assertTrue($activeProducers->every(fn($producer) => 
            $producer->process_id === $processId && 
            $producer->status === 'active'
        ));
    }
}
