<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Payload;
use App\Repositories\PayloadRepository;
use Illuminate\Foundation\Testing\WithFaker;

class PayloadRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private PayloadRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PayloadRepository();
    }

    public function test_can_create_payload()
    {
        $productionLineId = 1;
        $payloadData = [
            'production_line_id' => $productionLineId,
            'payload' => json_encode([
                'topic' => 'production/process/1/count',
                'count' => 10
            ])
        ];

        $request = new TestFormRequest($payloadData);
        $result = $this->repository->store($request);

        $this->assertInstanceOf(Payload::class, $payload);
        $this->assertTrue($result);
        $payload = Payload::where('production_line_id', $productionLineId)->first();
        $this->assertInstanceOf(Payload::class, $payload);
        $this->assertEquals($productionLineId, $payload->production_line_id);
        $this->assertEquals($payloadData['payload'], $payload->payload);
    }

    public function test_can_find_payload_by_id()
    {
        $payload = Payload::factory()->create();

        $found = $this->repository->find($payload->id);

        $this->assertInstanceOf(Payload::class, $found);
        $this->assertEquals($payload->id, $found->id);
    }

    public function test_can_get_payloads_by_process()
    {
        $processId = 1;
        $payloads = Payload::factory()->count(3)->create([
            'process_id' => $processId
        ]);
        Payload::factory()->create(['process_id' => 2]); // Different process

        $found = $this->repository->getByProcessId($processId);

        $this->assertCount(3, $found);
        $this->assertTrue($found->every(fn($p) => $p->process_id === $processId));
    }

    public function test_can_get_payloads_by_topic()
    {
        $topic = 'production/process/1/count';
        $payloads = Payload::factory()->count(2)->create([
            'topic' => $topic
        ]);
        Payload::factory()->create(['topic' => 'different/topic']); // Different topic

        $found = $this->repository->getByTopic($topic);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($p) => $p->topic === $topic));
    }
}
