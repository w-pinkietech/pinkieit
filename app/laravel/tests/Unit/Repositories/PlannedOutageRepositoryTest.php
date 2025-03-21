<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\PlannedOutage;
use App\Repositories\PlannedOutageRepository;
use Illuminate\Foundation\Testing\WithFaker;

class PlannedOutageRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private PlannedOutageRepository $repository;
    private $model = PlannedOutage::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PlannedOutageRepository();
    }

    public function test_can_create_planned_outage()
    {
        $data = [
            'name' => 'Maintenance Break',
            'description' => 'Regular maintenance',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ];

        $model = new $this->model($data);
        $this->repository->storeModel($model);

        $this->assertInstanceOf(PlannedOutage::class, $plannedOutage);
        $this->assertEquals($data['name'], $plannedOutage->name);
        $this->assertEquals($data['description'], $plannedOutage->description);
        $this->assertEquals($data['start_time'], $plannedOutage->start_time);
        $this->assertEquals($data['end_time'], $plannedOutage->end_time);
    }

    public function test_can_find_planned_outage_by_id()
    {
        $plannedOutage = PlannedOutage::factory()->create();

        $found = $this->repository->find($plannedOutage->id);

        $this->assertInstanceOf(PlannedOutage::class, $found);
        $this->assertEquals($plannedOutage->id, $found->id);
    }

    public function test_can_update_planned_outage()
    {
        $plannedOutage = PlannedOutage::factory()->create([
            'name' => 'Old Name'
        ]);

        $updated = $this->repository->update($plannedOutage->id, [
            'name' => 'New Name'
        ]);

        $this->assertEquals('New Name', $updated->name);
    }

    public function test_can_get_active_planned_outages()
    {
        PlannedOutage::factory()->create(['active' => true]);
        PlannedOutage::factory()->create(['active' => true]);
        PlannedOutage::factory()->create(['active' => false]);

        $activeOutages = $this->repository->getActive();

        $this->assertCount(2, $activeOutages);
        $this->assertTrue($activeOutages->every(fn($outage) => $outage->active));
    }
}
