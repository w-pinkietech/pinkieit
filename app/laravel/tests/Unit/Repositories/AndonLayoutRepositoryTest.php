<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\AndonLayout;
use App\Repositories\AndonLayoutRepository;
use Illuminate\Foundation\Testing\WithFaker;

class AndonLayoutRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private AndonLayoutRepository $repository;
    private $model = AndonLayout::class;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new AndonLayoutRepository();
    }

    public function test_can_create_andon_layout()
    {
        $data = [
            'process_id' => 1,
            'layout' => json_encode([
                'columns' => 3,
                'rows' => 2,
                'elements' => [
                    ['type' => 'indicator', 'position' => [0, 0]],
                    ['type' => 'counter', 'position' => [1, 0]]
                ]
            ]),
            'active' => true,
        ];

        $request = new TestFormRequest($data);
        $result = $this->repository->store($request);

        $this->assertTrue($result);
        $layout = AndonLayout::where('process_id', $data['process_id'])->first();
        $this->assertInstanceOf(AndonLayout::class, $layout);
        $this->assertEquals($data['process_id'], $layout->process_id);
        $this->assertEquals($data['layout'], $layout->layout);
        $this->assertEquals($data['active'], $layout->active);
    }

    public function test_can_find_andon_layout_by_id()
    {
        $layout = AndonLayout::factory()->create();

        $found = $this->repository->find($layout->id);

        $this->assertInstanceOf(AndonLayout::class, $found);
        $this->assertEquals($layout->id, $found->id);
    }

    public function test_can_update_andon_layout()
    {
        $layout = AndonLayout::factory()->create([
            'active' => false
        ]);

        $request = new TestFormRequest([
            'active' => true
        ]);
        $result = $this->repository->update($request, $layout);

        $this->assertTrue($result);
        $this->assertTrue($layout->fresh()->active);
    }

    public function test_can_get_active_layouts_by_process()
    {
        $processId = 1;
        AndonLayout::factory()->count(2)->create([
            'process_id' => $processId,
            'active' => true
        ]);
        AndonLayout::factory()->create([
            'process_id' => $processId,
            'active' => false
        ]);

        $activeLayouts = $this->repository->getActiveByProcessId($processId);

        $this->assertCount(2, $activeLayouts);
        $this->assertTrue($activeLayouts->every(fn($layout) => 
            $layout->process_id === $processId && $layout->active
        ));
    }
}
