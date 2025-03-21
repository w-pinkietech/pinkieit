<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\BarcodeHistory;
use App\Repositories\BarcodeHistoryRepository;
use Illuminate\Foundation\Testing\WithFaker;

class BarcodeHistoryRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private BarcodeHistoryRepository $repository;
    private $model = BarcodeHistory::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BarcodeHistoryRepository();
    }

    public function test_can_create_barcode_history()
    {
        $data = [
            'process_id' => 1,
            'barcode' => 'ABC123XYZ',
            'scanned_at' => now(),
        ];

        $history = new $this->model($data);
        $this->repository->storeModel($history);

        $this->assertInstanceOf(BarcodeHistory::class, $history);
        $this->assertEquals($data['process_id'], $history->process_id);
        $this->assertEquals($data['barcode'], $history->barcode);
        $this->assertEquals($data['scanned_at']->timestamp, $history->scanned_at->timestamp);
    }

    public function test_can_find_barcode_history_by_id()
    {
        $history = BarcodeHistory::factory()->create();

        $found = $this->repository->find($history->id);

        $this->assertInstanceOf(BarcodeHistory::class, $found);
        $this->assertEquals($history->id, $found->id);
    }

    public function test_can_get_history_by_process()
    {
        $processId = 1;
        $histories = BarcodeHistory::factory()->count(3)->create([
            'process_id' => $processId
        ]);
        BarcodeHistory::factory()->create(['process_id' => 2]); // Different process

        $foundHistories = $this->repository->getByProcessId($processId);

        $this->assertCount(3, $foundHistories);
        $this->assertTrue($foundHistories->every(fn($history) => $history->process_id === $processId));
    }

    public function test_can_get_latest_barcode_for_process()
    {
        $processId = 1;
        BarcodeHistory::factory()->create([
            'process_id' => $processId,
            'scanned_at' => now()->subHour(),
            'barcode' => 'OLD123'
        ]);
        $latest = BarcodeHistory::factory()->create([
            'process_id' => $processId,
            'scanned_at' => now(),
            'barcode' => 'NEW456'
        ]);

        $foundLatest = $this->repository->getLatestByProcessId($processId);

        $this->assertEquals($latest->barcode, $foundLatest->barcode);
    }
}
