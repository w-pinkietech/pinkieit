<?php

namespace Tests\Unit\Models;

use App\Models\Line;
use App\Models\Process;
use App\Models\RaspberryPi;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineTest extends TestCase
{
    use RefreshDatabase;

    protected Line $line;

    protected function setUp(): void
    {
        parent::setUp();
        $this->line = Line::factory()->create();
    }

    /**
     * Test fillable attributes
     */
    public function test_fillable_attributes(): void
    {
        $fillable = [
            'process_id',
            'raspberry_pi_id',
            'worker_id',
            'parent_id',
            'line_name',
            'chart_color',
            'pin_number',
            'defective',
            'order',
        ];

        $this->assertEquals($fillable, $this->line->getFillable());
    }

    /**
     * Test defective attribute is cast to boolean
     */
    public function test_defective_is_cast_to_boolean(): void
    {
        $casts = $this->line->getCasts();

        $this->assertArrayHasKey('defective', $casts);
        $this->assertEquals('boolean', $casts['defective']);
    }

    /**
     * Test primary key
     */
    public function test_primary_key(): void
    {
        $this->assertEquals('line_id', $this->line->getKeyName());
    }

    /**
     * Test model extends pivot
     */
    public function test_extends_pivot(): void
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\Pivot::class, $this->line);
    }

    /**
     * Test factory creates valid model
     */
    public function test_factory_creates_valid_model(): void
    {
        $this->assertInstanceOf(Line::class, $this->line);
        $this->assertIsInt($this->line->line_id);
        $this->assertIsString($this->line->line_name);
        $this->assertIsBool($this->line->defective);
        $this->assertIsInt($this->line->order);
    }

    /**
     * Test pin number formatting method exists
     */
    public function test_pin_number_formatting(): void
    {
        $this->assertTrue(method_exists($this->line, 'pinNumber'));
    }

    /**
     * Test pin number method integration
     */
    public function test_pin_number_method_integration(): void
    {
        $this->line->pin_number = 5;

        // Test that the method exists and returns a string
        $result = $this->line->pinNumber();
        $this->assertIsString($result);

        // Test with different pin numbers
        $this->line->pin_number = 18;
        $result2 = $this->line->pinNumber();
        $this->assertIsString($result2);

        // Results should be different for different pin numbers
        $this->assertNotEquals($result, $result2);
    }

    /**
     * Test defective boolean casting
     */
    public function test_defective_boolean_casting(): void
    {
        $this->line->defective = 1;
        $this->line->save();
        $this->line->refresh();

        $this->assertIsBool($this->line->defective);
        $this->assertTrue($this->line->defective);

        $this->line->defective = 0;
        $this->line->save();
        $this->line->refresh();

        $this->assertIsBool($this->line->defective);
        $this->assertFalse($this->line->defective);
    }

    /**
     * Test worker relationship
     */
    public function test_worker_relationship(): void
    {
        $worker = Worker::factory()->create();
        $this->line->update(['worker_id' => $worker->worker_id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $this->line->worker());
        $this->assertEquals($worker->worker_id, $this->line->worker->worker_id);
    }

    /**
     * Test process relationship
     */
    public function test_process_relationship(): void
    {
        $process = Process::factory()->create();
        $this->line->update(['process_id' => $process->process_id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $this->line->process());
        $this->assertEquals($process->process_id, $this->line->process->process_id);
    }

    /**
     * Test raspberry pi relationship
     */
    public function test_raspberry_pi_relationship(): void
    {
        $raspberryPi = RaspberryPi::factory()->create();
        $this->line->update(['raspberry_pi_id' => $raspberryPi->raspberry_pi_id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $this->line->raspberryPi());
        $this->assertEquals($raspberryPi->raspberry_pi_id, $this->line->raspberryPi->raspberry_pi_id);
    }

    /**
     * Test parent line relationship (self-referential)
     */
    public function test_parent_line_relationship(): void
    {
        $parentLine = Line::factory()->create();
        $this->line->update(['parent_id' => $parentLine->line_id]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $this->line->parentLine());
        $this->assertEquals($parentLine->line_id, $this->line->parentLine->line_id);
    }

    /**
     * Test defective lines relationship (self-referential)
     */
    public function test_defective_lines_relationship(): void
    {
        $defectiveLine = Line::factory()->create([
            'parent_id' => $this->line->line_id,
            'defective' => true,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $this->line->defectiveLines());
        $this->assertTrue($this->line->defectiveLines->contains($defectiveLine));
    }

    /**
     * Test hidden attributes
     */
    public function test_hidden_attributes(): void
    {
        $hidden = ['created_at', 'updated_at'];

        $this->assertEquals($hidden, $this->line->getHidden());
    }

    /**
     * Test model serialization hides timestamps
     */
    public function test_model_serialization_hides_timestamps(): void
    {
        $array = $this->line->toArray();

        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
    }

    /**
     * Test line ordering
     */
    public function test_line_ordering(): void
    {
        // Create lines with specific orders
        $line1 = Line::factory()->create(['order' => 100]);
        Line::factory()->create(['order' => 200]);
        $line3 = Line::factory()->create(['order' => 300]);

        // Query specifically for these lines by their orders
        $orderedLines = Line::whereIn('order', [100, 200, 300])->orderBy('order')->get();

        $this->assertEquals($line1->line_id, $orderedLines->first()->line_id);
        $this->assertEquals($line3->line_id, $orderedLines->last()->line_id);
        $this->assertCount(3, $orderedLines);
    }

    /**
     * Test nullable relationships
     */
    public function test_nullable_relationships(): void
    {
        $line = Line::factory()->create([
            'worker_id' => null,
            'parent_id' => null,
        ]);

        $this->assertNull($line->worker);
        $this->assertNull($line->parentLine);
    }

    /**
     * Test chart color validation
     */
    public function test_chart_color_field(): void
    {
        $this->line->update(['chart_color' => '#FF5733']);
        $this->assertEquals('#FF5733', $this->line->chart_color);
    }

    /**
     * Test pin number range
     */
    public function test_pin_number_field(): void
    {
        $this->line->update(['pin_number' => 18]);
        $this->assertEquals(18, $this->line->pin_number);
        $this->assertIsInt($this->line->pin_number);
    }
}
