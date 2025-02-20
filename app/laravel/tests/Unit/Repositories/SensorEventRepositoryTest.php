<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\SensorEvent;
use App\Repositories\SensorEventRepository;
use App\Enums\SensorType;
use Illuminate\Foundation\Testing\WithFaker;

class SensorEventRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private SensorEventRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SensorEventRepository();
    }

    public function test_can_create_sensor_event()
    {
        $data = [
            'sensor_id' => 1,
            'type' => SensorType::Count,
            'value' => '10',
            'identification_number' => 'SENSOR-001',
            'recorded_at' => now(),
        ];

        $event = $this->repository->create($data);

        $this->assertInstanceOf(SensorEvent::class, $event);
        $this->assertEquals($data['sensor_id'], $event->sensor_id);
        $this->assertEquals($data['type'], $event->type);
        $this->assertEquals($data['value'], $event->value);
        $this->assertEquals($data['identification_number'], $event->identification_number);
        $this->assertEquals($data['recorded_at']->timestamp, $event->recorded_at->timestamp);
    }

    public function test_can_find_sensor_event_by_id()
    {
        $event = SensorEvent::factory()->create();

        $found = $this->repository->find($event->id);

        $this->assertInstanceOf(SensorEvent::class, $found);
        $this->assertEquals($event->id, $found->id);
    }

    public function test_can_get_events_by_sensor()
    {
        $sensorId = 1;
        $events = SensorEvent::factory()->count(3)->create([
            'sensor_id' => $sensorId
        ]);
        SensorEvent::factory()->create(['sensor_id' => 2]); // Different sensor

        $found = $this->repository->getBySensorId($sensorId);

        $this->assertCount(3, $found);
        $this->assertTrue($found->every(fn($event) => $event->sensor_id === $sensorId));
    }

    public function test_can_get_events_by_time_range()
    {
        $startTime = now()->subHour();
        $endTime = now();

        SensorEvent::factory()->create([
            'recorded_at' => now()->subHours(2)
        ]); // Outside range
        SensorEvent::factory()->count(2)->create([
            'recorded_at' => now()->subMinutes(30)
        ]); // Inside range

        $found = $this->repository->getByTimeRange($startTime, $endTime);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($event) => 
            $event->recorded_at->greaterThanOrEqualTo($startTime) &&
            $event->recorded_at->lessThanOrEqualTo($endTime)
        ));
    }
}
