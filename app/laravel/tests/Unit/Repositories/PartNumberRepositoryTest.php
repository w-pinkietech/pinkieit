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
            'part_number_name' => 'Old Name'
        ]);

        $updated = $this->repository->update($partNumber->id, [
            'part_number_name' => 'New Name'
        ]);

        $this->assertEquals('New Name', $updated->part_number_name);
    }

    public function test_can_get_all_part_numbers()
    {
        PartNumber::factory()->count(3)->create();

        $partNumbers = $this->repository->all();

        $this->assertCount(3, $partNumbers);
        $this->assertTrue($partNumbers->every(fn($part) => $part instanceof PartNumber));
    }
}
