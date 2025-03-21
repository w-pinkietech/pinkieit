<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\AndonConfig;
use App\Repositories\AndonConfigRepository;
use App\Enums\AndonColumnSize;
use Illuminate\Foundation\Testing\WithFaker;

class AndonConfigRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private AndonConfigRepository $repository;
    private $model = AndonConfig::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AndonConfigRepository();
    }

    public function test_can_create_andon_config()
    {
        $data = [
            'user_id' => 1,
            'row_count' => 2,
            'column_count' => AndonColumnSize::ONE,
            'auto_play' => true,
            'auto_play_speed' => 3000,
            'slide_speed' => 500,
            'easing' => 'linear',
            'fade' => false,
        ];

        $config = new $this->model($data);
        $this->repository->storeModel($config);

        $this->assertInstanceOf(AndonConfig::class, $config);
        $this->assertEquals($data['user_id'], $config->user_id);
        $this->assertEquals($data['row_count'], $config->row_count);
        $this->assertEquals($data['column_count'], $config->column_count);
        $this->assertEquals($data['auto_play'], $config->auto_play);
        $this->assertEquals($data['auto_play_speed'], $config->auto_play_speed);
        $this->assertEquals($data['slide_speed'], $config->slide_speed);
        $this->assertEquals($data['easing'], $config->easing);
        $this->assertEquals($data['fade'], $config->fade);
    }

    public function test_can_find_andon_config_by_id()
    {
        $config = AndonConfig::factory()->create();

        $found = $this->repository->find($config->id);

        $this->assertInstanceOf(AndonConfig::class, $found);
        $this->assertEquals($config->id, $found->id);
    }

    public function test_can_update_andon_config()
    {
        $config = AndonConfig::factory()->create([
            'column_size' => AndonColumnSize::ONE
        ]);

        $updated = $this->repository->update($config->id, [
            'column_size' => AndonColumnSize::TWO
        ]);

        $this->assertEquals(AndonColumnSize::TWO, $updated->column_size);
    }

    public function test_can_get_configs_by_process()
    {
        $processId = 1;
        $configs = AndonConfig::factory()->count(3)->create([
            'process_id' => $processId
        ]);
        AndonConfig::factory()->create(['process_id' => 2]); // Different process

        $foundConfigs = $this->repository->getByProcessId($processId);

        $this->assertCount(3, $foundConfigs);
        $this->assertTrue($foundConfigs->every(fn($config) => $config->process_id === $processId));
    }
}
