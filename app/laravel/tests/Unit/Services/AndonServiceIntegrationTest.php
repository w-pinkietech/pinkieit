<?php

namespace Tests\Unit\Services;

use App\Http\Requests\UpdateAndonConfigRequest;
use App\Models\AndonConfig;
use App\Models\AndonLayout;
use App\Models\Process;
use App\Models\ProductionHistory;
use App\Models\ProductionLine;
use App\Models\User;
use App\Services\AndonService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class AndonServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected AndonService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AndonService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_processes_returns_correct_collection_type(): void
    {
        Process::factory()->count(2)->create();

        $result = $this->service->processes();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Process::class, $result);
    }

    public function test_processes_sorts_by_andon_layout_order(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $process1 = Process::factory()->create(['process_name' => 'Process 1']);
        $process2 = Process::factory()->create(['process_name' => 'Process 2']);
        $process3 = Process::factory()->create(['process_name' => 'Process 3']);

        // Create andon layouts with specific orders
        AndonLayout::factory()->create([
            'process_id' => $process1->process_id,
            'user_id' => $user->id,
            'order' => 3
        ]);
        AndonLayout::factory()->create([
            'process_id' => $process2->process_id,
            'user_id' => $user->id,
            'order' => 1
        ]);
        AndonLayout::factory()->create([
            'process_id' => $process3->process_id,
            'user_id' => $user->id,
            'order' => 2
        ]);

        $result = $this->service->processes();

        // Should be sorted by andon layout order, then by process_id
        $this->assertEquals($process2->process_id, $result->get(0)->process_id); // order: 1
        $this->assertEquals($process3->process_id, $result->get(1)->process_id); // order: 2
        $this->assertEquals($process1->process_id, $result->get(2)->process_id); // order: 3
    }

    public function test_processes_handles_null_production_history(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $process = Process::factory()->create(['production_history_id' => null]);
        AndonLayout::factory()->create([
            'process_id' => $process->process_id,
            'user_id' => $user->id,
            'order' => 1
        ]);

        $result = $this->service->processes();

        $this->assertCount(1, $result);
        $this->assertNull($result->first()->productionHistory);
        $this->assertFalse(isset($result->first()->production_summary));
    }

    public function test_andon_config_returns_config(): void
    {
        $user = User::factory()->create();
        $config = AndonConfig::factory()->create(['user_id' => $user->id]);
        
        Auth::shouldReceive('id')->andReturn($user->id);

        $result = $this->service->andonConfig();

        $this->assertInstanceOf(AndonConfig::class, $result);
        $this->assertEquals($config->andon_config_id, $result->andon_config_id);
    }

    public function test_update_successful_without_layouts(): void
    {
        $user = User::factory()->create();
        $config = AndonConfig::factory()->create(['user_id' => $user->id]);
        
        Auth::shouldReceive('id')->andReturn($user->id);

        $request = Mockery::mock(UpdateAndonConfigRequest::class);
        $request->shouldReceive('all')->andReturn([
            'font_size_percentage' => 120,
            'refresh_sec' => 30
        ]);
        $request->layouts = null;

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $this->service->update($request);

        // If no exception is thrown, the test passes
        $this->assertTrue(true);
    }

    public function test_processes_with_production_history_and_indicator_line(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create process with production history
        $productionHistory = ProductionHistory::factory()->create();
        $process = Process::factory()->create([
            'production_history_id' => $productionHistory->production_history_id
        ]);

        // Create production line as indicator line
        $productionLine = ProductionLine::factory()->create([
            'production_history_id' => $productionHistory->production_history_id,
            'indicator' => true
        ]);

        AndonLayout::factory()->create([
            'process_id' => $process->process_id,
            'user_id' => $user->id,
            'order' => 1
        ]);

        $result = $this->service->processes();

        $this->assertCount(1, $result);
        $this->assertNotNull($result->first()->productionHistory);
        $this->assertEquals($productionHistory->production_history_id, $result->first()->productionHistory->production_history_id);
    }

    public function test_processes_loads_all_required_relationships(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $process = Process::factory()->create();
        AndonLayout::factory()->create([
            'process_id' => $process->process_id,
            'user_id' => $user->id,
            'order' => 1
        ]);

        $result = $this->service->processes();

        $processResult = $result->first();
        
        // Check that relationships are loaded
        $this->assertTrue($processResult->relationLoaded('andonLayout'));
        $this->assertTrue($processResult->relationLoaded('sensorEvents'));
        $this->assertTrue($processResult->relationLoaded('productionHistory'));
    }

    public function test_processes_without_authenticated_user(): void
    {
        // Test processes method when no user is authenticated
        Process::factory()->count(2)->create();

        $result = $this->service->processes();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_andon_config_creates_new_when_not_exists(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $result = $this->service->andonConfig();

        $this->assertInstanceOf(AndonConfig::class, $result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertDatabaseHas('andon_configs', ['user_id' => $user->id]);
    }
}