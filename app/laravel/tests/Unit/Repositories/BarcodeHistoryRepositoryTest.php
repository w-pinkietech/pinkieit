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

            'barcode' => 'ABC123XYZ',
            'ip_address' => '192.168.1.1',
            'mac_address' => '00:11:22:33:44:55',
        ];

        $request = new TestFormRequest($data);
        $result = $this->repository->store($request);

        $this->assertTrue($result);
        $history = BarcodeHistory::where('barcode', $data['barcode'])->first();
        $this->assertInstanceOf(BarcodeHistory::class, $history);
        $this->assertEquals($data['barcode'], $history->barcode);
        $this->assertEquals($data['ip_address'], $history->ip_address);
        $this->assertEquals($data['mac_address'], $history->mac_address);
    }

    public function test_can_find_barcode_history_by_id()
    {
        $history = BarcodeHistory::factory()->create();

        $found = $this->repository->find($history->id);

        $this->assertInstanceOf(BarcodeHistory::class, $found);
        $this->assertEquals($history->id, $found->id);
    }

    public function test_can_get_latest_barcode()
    {
        BarcodeHistory::factory()->create([
            'barcode' => 'OLD123',
            'created_at' => now()->subHour()
        ]);
        $latest = BarcodeHistory::factory()->create([
            'barcode' => 'NEW456',
            'created_at' => now()
        ]);

        $foundLatest = $this->repository->getLatest();

        $this->assertEquals($latest->barcode, $foundLatest->barcode);
    }
}
