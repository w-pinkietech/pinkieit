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
            'process_id' => 1,
            'column_size' => AndonColumnSize::ONE,
            'indicator_id' => 'indicator-1',
            'easing_type' => 'linear',
        ];

        $config = new $this->model($data);
        $this->repository->storeModel($config);

        $this->assertInstanceOf(AndonConfig::class, $config);
        $this->assertEquals($data['process_id'], $config->process_id);
        $this->assertEquals($data['column_size'], $config->column_size);
        $this->assertEquals($data['indicator_id'], $config->indicator_id);
        $this->assertEquals($data['easing_type'], $config->easing_type);
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
            'column_size' => AndonColumnSize::Small
        ]);

        $updated = $this->repository->update($config->id, [
            'column_size' => AndonColumnSize::Large
        ]);

        $this->assertEquals(AndonColumnSize::Large, $updated->column_size);
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
