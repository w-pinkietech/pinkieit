<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\PartNumber;
use App\Repositories\PartNumberRepository;
use Illuminate\Foundation\Testing\WithFaker;

class PartNumberRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private PartNumberRepository $repository;
    private $model = PartNumber::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new PartNumberRepository();
    }

    public function test_can_create_part_number()
    {
        $data = [
            'part_number_name' => 'Test Part',
            'remark' => 'Test remark',
        ];

        $partNumber = new $this->model($data);
        $this->repository->storeModel($partNumber);

        $this->assertInstanceOf(PartNumber::class, $partNumber);
        $this->assertEquals($data['part_number_name'], $partNumber->part_number_name);
        $this->assertEquals($data['remark'], $partNumber->remark);
    }

    public function test_can_find_part_number_by_id()
    {
        $partNumber = PartNumber::factory()->create();

        $found = $this->repository->find($partNumber->id);

        $this->assertInstanceOf(PartNumber::class, $found);
        $this->assertEquals($partNumber->id, $found->id);
    }

    public function test_can_update_part_number()
    {
        $partNumber = PartNumber::factory()->create([
            'description' => 'Old Description'
        ]);

        $updated = $this->repository->update($partNumber->id, [
            'description' => 'New Description'
        ]);

        $this->assertEquals('New Description', $updated->description);
    }

    public function test_can_get_active_part_numbers()
    {
        PartNumber::factory()->create(['active' => true]);
        PartNumber::factory()->create(['active' => true]);
        PartNumber::factory()->create(['active' => false]);

        $activePartNumbers = $this->repository->getActive();

        $this->assertCount(2, $activePartNumbers);
        $this->assertTrue($activePartNumbers->every(fn($part) => $part->active));
    }
}
