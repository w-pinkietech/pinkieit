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

        $this->assertTrue($result);
        $payload = Payload::where('production_line_id', $payloadData['production_line_id'])->first();
        $this->assertInstanceOf(Payload::class, $payload);
        $this->assertEquals($payloadData['production_line_id'], $payload->production_line_id);
        $this->assertEquals($payloadData['payload'], $payload->payload);
    }

    public function test_can_find_payload_by_id()
    {
        $payload = Payload::factory()->create();

        $found = $this->repository->find($payload->id);

        $this->assertInstanceOf(Payload::class, $found);
        $this->assertEquals($payload->id, $found->id);
    }


}
