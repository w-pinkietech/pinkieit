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
            'planned_outage_name' => 'Maintenance Break',
            'start_time' => '09:00',
            'end_time' => '10:00',
        ];

        $request = new TestFormRequest($data);
        $result = $this->repository->store($request);

        $this->assertTrue($result);
        $plannedOutage = PlannedOutage::where('planned_outage_name', $data['planned_outage_name'])->first();
        $this->assertInstanceOf(PlannedOutage::class, $plannedOutage);
        $this->assertEquals($data['planned_outage_name'], $plannedOutage->planned_outage_name);
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
            'planned_outage_name' => 'Old Name'
        ]);

        $request = new TestFormRequest([
            'planned_outage_name' => 'New Name'
        ]);
        $result = $this->repository->update($request, $plannedOutage);

        $this->assertEquals('New Name', $updated->planned_outage_name);
    }

    public function test_can_get_current_planned_outages()
    {
        $now = now();
        PlannedOutage::factory()->create([
            'start_time' => '08:00',
            'end_time' => '10:00'
        ]);
        PlannedOutage::factory()->create([
            'start_time' => '09:00',
            'end_time' => '11:00'
        ]);
        PlannedOutage::factory()->create([
            'start_time' => '14:00',
            'end_time' => '16:00'
        ]);

        $currentOutages = $this->repository->getCurrentOutages();

        $this->assertCount(2, $currentOutages);
    }
}
