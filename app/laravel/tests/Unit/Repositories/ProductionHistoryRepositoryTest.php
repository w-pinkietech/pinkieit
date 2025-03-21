<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\ProductionHistory;
use App\Repositories\ProductionHistoryRepository;
use Illuminate\Foundation\Testing\WithFaker;

class ProductionHistoryRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private ProductionHistoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductionHistoryRepository();
    }

    public function test_can_create_production_history()
    {
        $data = [
            'production_id' => 1,
            'part_number_id' => 1,
            'line_id' => 1,
            'target_count' => 100,
            'actual_count' => 95,
            'start' => now()->subHours(8),
            'finish' => now(),
        ];

        $history = new ProductionHistory($data);
        $this->repository->storeModel($history);

        $this->assertInstanceOf(ProductionHistory::class, $history);
        $this->assertEquals($data['production_id'], $history->production_id);
        $this->assertEquals($data['part_number_id'], $history->part_number_id);
        $this->assertEquals($data['line_id'], $history->line_id);
        $this->assertEquals($data['target_count'], $history->target_count);
        $this->assertEquals($data['actual_count'], $history->actual_count);
        $this->assertEquals($data['started_at']->timestamp, $history->started_at->timestamp);
        $this->assertEquals($data['finished_at']->timestamp, $history->finished_at->timestamp);
    }

    public function test_can_find_production_history_by_id()
    {
        $history = ProductionHistory::factory()->create();

        $found = $this->repository->find($history->id);

        $this->assertInstanceOf(ProductionHistory::class, $found);
        $this->assertEquals($history->id, $found->id);
    }

    public function test_can_get_history_by_line()
    {
        $lineId = 1;
        $histories = ProductionHistory::factory()->count(3)->create([
            'line_id' => $lineId
        ]);
        ProductionHistory::factory()->create(['line_id' => 2]); // Different line

        $found = $this->repository->getByLineId($lineId);

        $this->assertCount(3, $found);
        $this->assertTrue($found->every(fn($h) => $h->line_id === $lineId));
    }

    public function test_can_get_history_by_date_range()
    {
        $startDate = now()->subDays(7);
        $endDate = now();

        ProductionHistory::factory()->create([
            'start' => now()->subDays(10),
            'finish' => now()->subDays(9)
        ]); // Outside range
        ProductionHistory::factory()->count(2)->create([
            'start' => now()->subDays(5),
            'finish' => now()->subDays(4)
        ]); // Inside range

        $found = $this->repository->getByDateRange($startDate, $endDate);

        $this->assertCount(2, $found);
        $this->assertTrue($found->every(fn($h) => 
            $h->start->greaterThanOrEqualTo($startDate) &&
            $h->finish->lessThanOrEqualTo($endDate)
        ));
    }
}
