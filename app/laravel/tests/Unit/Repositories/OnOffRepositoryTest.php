<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\OnOff;
use App\Repositories\OnOffRepository;
use Illuminate\Foundation\Testing\WithFaker;

class OnOffRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private OnOffRepository $repository;
    private $model = OnOff::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OnOffRepository();
    }

    public function test_can_create_on_off()
    {
        $data = [
            'process_id' => 1,
            'raspberry_pi_id' => 1,
            'event_name' => 'power',
            'on_message' => 'Power On',
            'off_message' => 'Power Off',
            'pin_number' => 1,
        ];

        $request = new TestFormRequest($data);
        $result = $this->repository->store($request);

        $this->assertTrue($result);
        $onOff = OnOff::where('process_id', $data['process_id'])->first();
        $this->assertInstanceOf(OnOff::class, $onOff);
        $this->assertEquals($data['process_id'], $onOff->process_id);
        $this->assertEquals($data['event_name'], $onOff->event_name);
        $this->assertEquals($data['pin_number'], $onOff->pin_number);
    }

    public function test_can_find_on_off_by_id()
    {
        $onOff = OnOff::factory()->create();

        $found = $this->repository->find($onOff->id);

        $this->assertInstanceOf(OnOff::class, $found);
        $this->assertEquals($onOff->id, $found->id);
    }

    public function test_can_update_on_off()
    {
        $onOff = OnOff::factory()->create([
            'is_on' => true
        ]);

        $updated = $this->repository->update($onOff->id, [
            'is_on' => false
        ]);

        $this->assertFalse($updated->is_on);
    }

    public function test_can_get_latest_by_process()
    {
        $processId = 1;
        OnOff::factory()->create([
            'process_id' => $processId,
            'created_at' => now()->subHours(2)
        ]);
        $latest = OnOff::factory()->create([
            'process_id' => $processId,
            'created_at' => now()
        ]);

        $found = $this->repository->getLatestByProcess($processId);

        $this->assertEquals($latest->id, $found->id);
    }
}
