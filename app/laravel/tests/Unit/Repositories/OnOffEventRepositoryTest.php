<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\OnOffEvent;
use App\Repositories\OnOffEventRepository;
use Illuminate\Foundation\Testing\WithFaker;

class OnOffEventRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private OnOffEventRepository $repository;
    private $model = OnOffEvent::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new OnOffEventRepository();
    }

    public function test_can_create_on_off_event()
    {
        $data = [
            'process_id' => 1,
            'on_off_id' => 1,
            'event_name' => 'power_off',
            'message' => 'Scheduled maintenance',
            'on_off' => false,
            'pin_number' => 1,
            'at' => now(),
        ];

        $request = new TestFormRequest($data);
        $result = $this->repository->store($request);

        $this->assertTrue($result);
        $event = OnOffEvent::where('on_off_id', $data['on_off_id'])->first();
        $this->assertInstanceOf(OnOffEvent::class, $event);
        $this->assertEquals($data['process_id'], $event->process_id);
        $this->assertEquals($data['on_off_id'], $event->on_off_id);
        $this->assertEquals($data['event_name'], $event->event_name);
        $this->assertEquals($data['message'], $event->message);
        $this->assertEquals($data['on_off'], $event->on_off);
        $this->assertEquals($data['pin_number'], $event->pin_number);
        $this->assertEquals($data['at']->timestamp, $event->at->timestamp);
    }

    public function test_can_find_on_off_event_by_id()
    {
        $event = OnOffEvent::factory()->create();

        $found = $this->repository->find($event->id);

        $this->assertInstanceOf(OnOffEvent::class, $found);
        $this->assertEquals($event->id, $found->id);
    }

    public function test_can_get_events_by_on_off_id()
    {
        $onOffId = 1;
        $events = OnOffEvent::factory()->count(3)->create([
            'on_off_id' => $onOffId
        ]);
        OnOffEvent::factory()->create(['on_off_id' => 2]); // Different on_off

        $found = $this->repository->getByOnOffId($onOffId);

        $this->assertCount(3, $found);
        $this->assertTrue($found->every(fn($event) => $event->on_off_id === $onOffId));
    }

    public function test_can_get_events_by_date_range()
    {
        $startDate = now()->subDay();
        $endDate = now();

        OnOffEvent::factory()->create([
            'at' => now()->subDays(2)
        ]); // Outside range
        OnOffEvent::factory()->count(2)->create([
            'at' => now()->subHours(12)
        ]); // Inside range

        $found = $this->repository->getByDateRange($startDate, $endDate);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($event) => 
            $event->at->greaterThanOrEqualTo($startDate) &&
            $event->at->lessThanOrEqualTo($endDate)
        ));
    }
}
