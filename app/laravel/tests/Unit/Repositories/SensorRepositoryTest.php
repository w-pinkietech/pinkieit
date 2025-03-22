<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\Sensor;
use App\Repositories\SensorRepository;
use App\Enums\SensorType;
use Illuminate\Foundation\Testing\WithFaker;

class SensorRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private SensorRepository $repository;
    protected $model = Sensor::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SensorRepository();
    }

    public function test_can_create_sensor()
    {
        $data = [
            'process_id' => 1,
            'type' => SensorType::Count,
            'identification_number' => 'SENSOR-001',
            'name' => 'Test Sensor',
        ];

        $sensor = new $this->model($data);
        $this->repository->storeModel($sensor);

        $this->assertInstanceOf(Sensor::class, $sensor);
        $this->assertEquals($data['process_id'], $sensor->process_id);
        $this->assertEquals($data['type'], $sensor->type);
        $this->assertEquals($data['identification_number'], $sensor->identification_number);
        $this->assertEquals($data['name'], $sensor->name);
    }

    public function test_can_find_sensor_by_id()
    {
        $sensor = Sensor::factory()->create();

        $found = $this->repository->find($sensor->id);

        $this->assertInstanceOf(Sensor::class, $found);
        $this->assertEquals($sensor->id, $found->id);
    }

    public function test_can_update_sensor()
    {
        $sensor = Sensor::factory()->create([
            'name' => 'Old Name'
        ]);

        $updated = $this->repository->update($sensor->id, [
            'name' => 'New Name'
        ]);

        $this->assertEquals('New Name', $updated->name);
    }
}
