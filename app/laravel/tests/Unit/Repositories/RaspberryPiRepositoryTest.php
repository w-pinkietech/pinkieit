<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\RaspberryPi;
use App\Repositories\RaspberryPiRepository;
use Illuminate\Foundation\Testing\WithFaker;

class RaspberryPiRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private RaspberryPiRepository $repository;
    private $model = RaspberryPi::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new RaspberryPiRepository();
    }

    public function test_can_create_raspberry_pi()
    {
        $data = [
            'process_id' => 1,
            'name' => 'RPi-001',
            'ip_address' => '192.168.1.100',
            'mac_address' => '00:11:22:33:44:55',
            'description' => 'Production line sensor hub',
        ];

        $raspberryPi = new $this->model($data);
        $this->repository->storeModel($raspberryPi);

        $this->assertInstanceOf(RaspberryPi::class, $raspberryPi);
        $this->assertEquals($data['process_id'], $raspberryPi->process_id);
        $this->assertEquals($data['name'], $raspberryPi->name);
        $this->assertEquals($data['ip_address'], $raspberryPi->ip_address);
        $this->assertEquals($data['mac_address'], $raspberryPi->mac_address);
        $this->assertEquals($data['description'], $raspberryPi->description);
    }

    public function test_can_find_raspberry_pi_by_id()
    {
        $raspberryPi = RaspberryPi::factory()->create();

        $found = $this->repository->find($raspberryPi->id);

        $this->assertInstanceOf(RaspberryPi::class, $found);
        $this->assertEquals($raspberryPi->id, $found->id);
    }

    public function test_can_update_raspberry_pi()
    {
        $raspberryPi = RaspberryPi::factory()->create([
            'ip_address' => '192.168.1.100'
        ]);

        $updated = $this->repository->update($raspberryPi->id, [
            'ip_address' => '192.168.1.200'
        ]);

        $this->assertEquals('192.168.1.200', $updated->ip_address);
    }

    public function test_can_get_raspberry_pis_by_process()
    {
        $processId = 1;
        $raspberryPis = RaspberryPi::factory()->count(2)->create([
            'process_id' => $processId
        ]);
        RaspberryPi::factory()->create(['process_id' => 2]); // Different process

        $found = $this->repository->getByProcessId($processId);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($rpi) => $rpi->process_id === $processId));
    }
}
