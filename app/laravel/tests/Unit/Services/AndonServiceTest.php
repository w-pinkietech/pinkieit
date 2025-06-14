<?php

namespace Tests\Unit\Services;

use App\Http\Requests\UpdateAndonConfigRequest;
use App\Models\AndonConfig;
use App\Models\AndonLayout;
use App\Models\Process;
use App\Models\ProductionHistory;
use App\Models\User;
use App\Repositories\AndonConfigRepository;
use App\Repositories\AndonLayoutRepository;
use App\Repositories\ProcessRepository;
use App\Services\AndonService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class AndonServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AndonService $service;
    protected $mockAndonConfigRepo;
    protected $mockAndonLayoutRepo;
    protected $mockProcessRepo;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mocks for repositories
        $this->mockAndonConfigRepo = Mockery::mock(AndonConfigRepository::class);
        $this->mockAndonLayoutRepo = Mockery::mock(AndonLayoutRepository::class);
        $this->mockProcessRepo = Mockery::mock(ProcessRepository::class);
        
        // Bind mocks to the container
        App::instance(AndonConfigRepository::class, $this->mockAndonConfigRepo);
        App::instance(AndonLayoutRepository::class, $this->mockAndonLayoutRepo);
        App::instance(ProcessRepository::class, $this->mockProcessRepo);
        
        $this->service = new AndonService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_processes_returns_correct_collection_type(): void
    {
        $processes = new Collection([
            Process::factory()->make(['process_id' => 1]),
            Process::factory()->make(['process_id' => 2]),
        ]);

        $this->mockProcessRepo
            ->shouldReceive('all')
            ->with([
                'andonLayout',
                'sensorEvents',
                'productionHistory.indicatorLine.payload',
            ])
            ->once()
            ->andReturn($processes);

        $result = $this->service->processes();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Process::class, $result);
    }

    public function test_processes_applies_production_summary(): void
    {
        $payload = Mockery::mock();
        $payload->shouldReceive('getPayloadData')
            ->once()
            ->andReturn(['data' => 'test']);

        $indicatorLine = Mockery::mock();
        $indicatorLine->payload = $payload;

        $productionHistory = Mockery::mock(ProductionHistory::class);
        $productionHistory->indicatorLine = $indicatorLine;
        $productionHistory->shouldReceive('makeProductionSummary')
            ->with(['data' => 'test'])
            ->once()
            ->andReturn(['summary' => 'test summary']);

        $process = Process::factory()->make(['process_id' => 1]);
        $process->productionHistory = $productionHistory;

        $processes = new Collection([$process]);

        $this->mockProcessRepo
            ->shouldReceive('all')
            ->with([
                'andonLayout',
                'sensorEvents',
                'productionHistory.indicatorLine.payload',
            ])
            ->once()
            ->andReturn($processes);

        $result = $this->service->processes();

        $processResult = $result->first();
        $this->assertObjectHasProperty('production_summary', $processResult);
        $this->assertEquals(['summary' => 'test summary'], $processResult->production_summary);
    }

    public function test_processes_handles_null_production_history(): void
    {
        $process = Process::factory()->make(['process_id' => 1]);
        $process->productionHistory = null;

        $processes = new Collection([$process]);

        $this->mockProcessRepo
            ->shouldReceive('all')
            ->with([
                'andonLayout',
                'sensorEvents',
                'productionHistory.indicatorLine.payload',
            ])
            ->once()
            ->andReturn($processes);

        $result = $this->service->processes();

        $this->assertNull($result->first()->production_summary ?? null);
    }

    public function test_processes_sorts_by_andon_layout_order(): void
    {
        $andonLayout1 = AndonLayout::factory()->make(['order' => 2]);
        $andonLayout2 = AndonLayout::factory()->make(['order' => 1]);
        $andonLayout3 = AndonLayout::factory()->make(['order' => 1]);

        $process1 = Process::factory()->make(['process_id' => 1]);
        $process1->setRelation('andonLayout', $andonLayout1);
        
        $process2 = Process::factory()->make(['process_id' => 2]);
        $process2->setRelation('andonLayout', $andonLayout2);
        
        $process3 = Process::factory()->make(['process_id' => 3]);
        $process3->setRelation('andonLayout', $andonLayout3);

        $processes = new Collection([$process1, $process2, $process3]);

        $this->mockProcessRepo
            ->shouldReceive('all')
            ->with([
                'andonLayout',
                'sensorEvents',
                'productionHistory.indicatorLine.payload',
            ])
            ->once()
            ->andReturn($processes);

        $result = $this->service->processes();

        // Check sorting: first by andonLayout.order, then by process_id
        $this->assertEquals(2, $result->get(0)->process_id); // order: 1, process_id: 2
        $this->assertEquals(3, $result->get(1)->process_id); // order: 1, process_id: 3
        $this->assertEquals(1, $result->get(2)->process_id); // order: 2, process_id: 1
    }

    public function test_andon_config_returns_config(): void
    {
        $config = AndonConfig::factory()->make();

        $this->mockAndonConfigRepo
            ->shouldReceive('andonConfig')
            ->once()
            ->andReturn($config);

        $result = $this->service->andonConfig();

        $this->assertSame($config, $result);
    }

    public function test_update_successful_with_layouts(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $config = AndonConfig::factory()->make();
        $request = Mockery::mock(UpdateAndonConfigRequest::class);
        $request->shouldReceive('all')->andReturn(['data' => 'test']);
        $request->layouts = [['layout' => 'data']];

        $this->mockAndonConfigRepo
            ->shouldReceive('andonConfig')
            ->once()
            ->andReturn($config);

        $this->mockAndonConfigRepo
            ->shouldReceive('update')
            ->with($request, $config)
            ->once()
            ->andReturn(true);

        $this->mockAndonLayoutRepo
            ->shouldReceive('updateLayouts')
            ->with([['layout' => 'data']], $user->id)
            ->once()
            ->andReturn(true);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->service->update($request);

        // If no exception is thrown, the test passes
        $this->assertTrue(true);
    }

    public function test_update_throws_exception_when_config_update_fails(): void
    {
        $config = AndonConfig::factory()->make();
        $request = Mockery::mock(UpdateAndonConfigRequest::class);
        $request->shouldReceive('all')->andReturn(['data' => 'test']);
        $request->layouts = null;

        $this->mockAndonConfigRepo
            ->shouldReceive('andonConfig')
            ->once()
            ->andReturn($config);

        $this->mockAndonConfigRepo
            ->shouldReceive('update')
            ->with($request, $config)
            ->once()
            ->andReturn(false);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->expectException(\Exception::class);

        $this->service->update($request);
    }

    public function test_update_throws_exception_when_layout_update_fails(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $config = AndonConfig::factory()->make();
        $request = Mockery::mock(UpdateAndonConfigRequest::class);
        $request->shouldReceive('all')->andReturn(['data' => 'test']);
        $request->layouts = [['layout' => 'data']];

        $this->mockAndonConfigRepo
            ->shouldReceive('andonConfig')
            ->once()
            ->andReturn($config);

        $this->mockAndonConfigRepo
            ->shouldReceive('update')
            ->with($request, $config)
            ->once()
            ->andReturn(true);

        $this->mockAndonLayoutRepo
            ->shouldReceive('updateLayouts')
            ->with([['layout' => 'data']], $user->id)
            ->once()
            ->andReturn(false);

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->expectException(ModelNotFoundException::class);

        $this->service->update($request);
    }

    public function test_update_without_layouts(): void
    {
        $config = AndonConfig::factory()->make();
        $request = Mockery::mock(UpdateAndonConfigRequest::class);
        $request->shouldReceive('all')->andReturn(['data' => 'test']);
        $request->layouts = null;

        $this->mockAndonConfigRepo
            ->shouldReceive('andonConfig')
            ->once()
            ->andReturn($config);

        $this->mockAndonConfigRepo
            ->shouldReceive('update')
            ->with($request, $config)
            ->once()
            ->andReturn(true);

        $this->mockAndonLayoutRepo
            ->shouldNotReceive('updateLayouts');

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->service->update($request);

        // If no exception is thrown, the test passes
        $this->assertTrue(true);
    }

    public function test_processes_with_complex_production_data(): void
    {
        $payload = Mockery::mock();
        $payload->shouldReceive('getPayloadData')
            ->once()
            ->andReturn([
                'production_count' => 100,
                'defective_count' => 5,
                'cycle_time' => 30
            ]);

        $indicatorLine = Mockery::mock();
        $indicatorLine->payload = $payload;

        $productionHistory = Mockery::mock(ProductionHistory::class);
        $productionHistory->indicatorLine = $indicatorLine;
        $productionHistory->shouldReceive('makeProductionSummary')
            ->with([
                'production_count' => 100,
                'defective_count' => 5,
                'cycle_time' => 30
            ])
            ->once()
            ->andReturn([
                'total' => 100,
                'defective' => 5,
                'efficiency' => 95.0
            ]);

        $process = Process::factory()->make(['process_id' => 1]);
        $process->productionHistory = $productionHistory;
        $process->setRelation('andonLayout', AndonLayout::factory()->make(['order' => 1]));

        $processes = new Collection([$process]);

        $this->mockProcessRepo
            ->shouldReceive('all')
            ->with([
                'andonLayout',
                'sensorEvents',
                'productionHistory.indicatorLine.payload',
            ])
            ->once()
            ->andReturn($processes);

        $result = $this->service->processes();

        $summary = $result->first()->production_summary;
        $this->assertEquals(100, $summary['total']);
        $this->assertEquals(5, $summary['defective']);
        $this->assertEquals(95.0, $summary['efficiency']);
    }
}