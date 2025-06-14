<?php

namespace Tests\Unit\Repositories;

use App\Models\AndonConfig;
use App\Models\User;
use App\Repositories\AndonConfigRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AndonConfigRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected AndonConfigRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AndonConfigRepository();
    }

    public function test_model_returns_correct_class_string(): void
    {
        $this->assertEquals(AndonConfig::class, $this->repository->model());
    }

    public function test_andon_config_creates_new_config_when_not_exists(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $config = $this->repository->andonConfig();

        $this->assertInstanceOf(AndonConfig::class, $config);
        $this->assertEquals($user->id, $config->user_id);
        
        // Verify it was saved to database
        $this->assertDatabaseHas('andon_configs', [
            'user_id' => $user->id
        ]);
    }

    public function test_andon_config_returns_existing_config(): void
    {
        $user = User::factory()->create();
        $existingConfig = AndonConfig::factory()->create([
            'user_id' => $user->id,
            'font_size_percentage' => 150,
            'refresh_sec' => 30
        ]);

        Auth::shouldReceive('id')->andReturn($user->id);

        $config = $this->repository->andonConfig();

        $this->assertInstanceOf(AndonConfig::class, $config);
        $this->assertEquals($existingConfig->andon_config_id, $config->andon_config_id);
        $this->assertEquals(150, $config->font_size_percentage);
        $this->assertEquals(30, $config->refresh_sec);
    }

    public function test_andon_config_creates_separate_configs_for_different_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create config for user1
        Auth::shouldReceive('id')->andReturn($user1->id);
        $config1 = $this->repository->andonConfig();

        // Create config for user2
        Auth::shouldReceive('id')->andReturn($user2->id);
        $config2 = $this->repository->andonConfig();

        $this->assertNotEquals($config1->andon_config_id, $config2->andon_config_id);
        $this->assertEquals($user1->id, $config1->user_id);
        $this->assertEquals($user2->id, $config2->user_id);

        // Verify both configs exist in database
        $this->assertEquals(2, AndonConfig::count());
    }

    public function test_andon_config_inherits_abstract_repository_functionality(): void
    {
        // Create some configs
        AndonConfig::factory()->count(3)->create();

        // Test all() method
        $allConfigs = $this->repository->all();
        $this->assertCount(3, $allConfigs);

        // Test find() method
        $config = AndonConfig::factory()->create(['refresh_sec' => 45]);
        $foundConfig = $this->repository->find($config->andon_config_id);
        $this->assertEquals(45, $foundConfig->refresh_sec);

        // Test first() method
        $user = User::factory()->create();
        AndonConfig::factory()->create(['user_id' => $user->id, 'clock' => 'show']);
        $specificConfig = $this->repository->first(['user_id' => $user->id]);
        $this->assertEquals('show', $specificConfig->clock);
    }

    public function test_andon_config_with_custom_settings(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        // First call creates with defaults
        $config = $this->repository->andonConfig();
        
        // Update the config
        $config->update([
            'font_size_percentage' => 200,
            'refresh_sec' => 60,
            'clock' => 'show',
            'page' => 'hidden',
            'goal' => 'show',
            'pace' => 'show',
            'changeover_time' => 'hidden',
            'downtime' => 'show',
            'sensor_display' => 'hidden',
            'all_production_view' => true,
            'auto_next_page_enable' => true,
            'auto_next_page_duration' => 120
        ]);

        // Second call should return the updated config
        Auth::shouldReceive('id')->andReturn($user->id);
        $retrievedConfig = $this->repository->andonConfig();

        $this->assertEquals(200, $retrievedConfig->font_size_percentage);
        $this->assertEquals(60, $retrievedConfig->refresh_sec);
        $this->assertEquals('show', $retrievedConfig->clock);
        $this->assertEquals('hidden', $retrievedConfig->page);
        $this->assertEquals('show', $retrievedConfig->goal);
        $this->assertEquals('show', $retrievedConfig->pace);
        $this->assertEquals('hidden', $retrievedConfig->changeover_time);
        $this->assertEquals('show', $retrievedConfig->downtime);
        $this->assertEquals('hidden', $retrievedConfig->sensor_display);
        $this->assertTrue($retrievedConfig->all_production_view);
        $this->assertTrue($retrievedConfig->auto_next_page_enable);
        $this->assertEquals(120, $retrievedConfig->auto_next_page_duration);
    }

    public function test_multiple_calls_return_same_instance_for_same_user(): void
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $config1 = $this->repository->andonConfig();
        
        Auth::shouldReceive('id')->andReturn($user->id);
        $config2 = $this->repository->andonConfig();

        $this->assertEquals($config1->andon_config_id, $config2->andon_config_id);
        
        // Should only have one config in database
        $this->assertEquals(1, AndonConfig::where('user_id', $user->id)->count());
    }

    public function test_andon_config_handles_null_user_id(): void
    {
        Auth::shouldReceive('id')->andReturn(null);

        $config = $this->repository->andonConfig();

        $this->assertInstanceOf(AndonConfig::class, $config);
        $this->assertNull($config->user_id);
        
        // Verify it was saved with null user_id
        $this->assertDatabaseHas('andon_configs', [
            'user_id' => null
        ]);
    }
}