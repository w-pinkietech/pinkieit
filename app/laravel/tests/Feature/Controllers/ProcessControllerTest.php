<?php

namespace Tests\Feature\Controllers;

use App\Models\Process;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProcessControllerTest extends BaseControllerTest
{
    use RefreshDatabase;

    /**
     * Test process index requires authentication
     */
    public function test_index_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('GET', '/process');
    }

    /**
     * Test authenticated user can access process index
     */
    public function test_index_accessible_when_authenticated(): void
    {
        $this->assertAuthenticatedAccess('GET', '/process');
    }

    /**
     * Test process index displays processes
     */
    public function test_index_displays_processes(): void
    {
        $process = Process::factory()->create(['name' => 'Test Process']);

        $response = $this->actingAs($this->user)->get('/process');

        $response->assertStatus(200);
        $response->assertViewIs('process.index');
        $response->assertViewHas('processes');
        $response->assertSee('Test Process');
    }

    /**
     * Test process create page requires authentication
     */
    public function test_create_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('GET', '/process/create');
    }

    /**
     * Test authenticated user can access process create
     */
    public function test_create_accessible_when_authenticated(): void
    {
        $this->assertAuthenticatedAccess('GET', '/process/create');
    }

    /**
     * Test process store requires authentication
     */
    public function test_store_requires_authentication(): void
    {
        $this->assertRequiresAuthentication('POST', '/process', ['name' => 'Test']);
    }

    /**
     * Test process store with valid data
     */
    public function test_store_with_valid_data(): void
    {
        $validData = [
            'name' => 'New Process',
            'description' => 'Test Description',
        ];

        $response = $this->actingAs($this->user)->post('/process', $validData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('processes', ['name' => 'New Process']);
    }

    /**
     * Test process store with invalid data
     */
    public function test_store_with_invalid_data(): void
    {
        $invalidData = [
            'name' => '', // Required field
        ];

        $this->assertValidationErrors('POST', '/process', $invalidData, ['name']);
    }

    /**
     * Test process show requires authentication
     */
    public function test_show_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('GET', "/process/{$process->process_id}");
    }

    /**
     * Test authenticated user can view process
     */
    public function test_show_accessible_when_authenticated(): void
    {
        $process = Process::factory()->create();
        $this->assertAuthenticatedAccess('GET', "/process/{$process->process_id}");
    }

    /**
     * Test process show displays correct data
     */
    public function test_show_displays_process_data(): void
    {
        $process = Process::factory()->create(['name' => 'Display Process']);

        $response = $this->actingAs($this->user)->get("/process/{$process->process_id}");

        $response->assertStatus(200);
        $response->assertSee('Display Process');
    }

    /**
     * Test process edit requires authentication
     */
    public function test_edit_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('GET', "/process/{$process->process_id}/edit");
    }

    /**
     * Test authenticated user can edit process
     */
    public function test_edit_accessible_when_authenticated(): void
    {
        $process = Process::factory()->create();
        $this->assertAuthenticatedAccess('GET', "/process/{$process->process_id}/edit");
    }

    /**
     * Test process update requires authentication
     */
    public function test_update_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('PUT', "/process/{$process->process_id}", ['name' => 'Updated']);
    }

    /**
     * Test process update with valid data
     */
    public function test_update_with_valid_data(): void
    {
        $process = Process::factory()->create();
        $updateData = [
            'name' => 'Updated Process',
            'description' => 'Updated Description',
        ];

        $response = $this->actingAs($this->user)->put("/process/{$process->process_id}", $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('processes', ['id' => $process->id, 'name' => 'Updated Process']);
    }

    /**
     * Test process update with invalid data
     */
    public function test_update_with_invalid_data(): void
    {
        $process = Process::factory()->create();
        $invalidData = [
            'name' => '', // Required field
        ];

        $this->assertValidationErrors('PUT', "/process/{$process->process_id}", $invalidData, ['name']);
    }

    /**
     * Test process delete requires authentication
     */
    public function test_destroy_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('DELETE', "/process/{$process->process_id}");
    }

    /**
     * Test process delete by authenticated user
     */
    public function test_destroy_removes_process(): void
    {
        $process = Process::factory()->create();

        $response = $this->actingAs($this->user)->delete("/process/{$process->process_id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('processes', ['id' => $process->id]);
    }

    /**
     * Test process select requires authentication
     */
    public function test_select_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('GET', "/process/{$process->process_id}/select");
    }

    /**
     * Test process switching requires authentication
     */
    public function test_switching_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('PUT', "/process/{$process->process_id}/switching");
    }

    /**
     * Test process stop requires authentication
     */
    public function test_stop_requires_authentication(): void
    {
        $process = Process::factory()->create();
        $this->assertRequiresAuthentication('PUT', "/process/{$process->process_id}/stop");
    }

    /**
     * Test 404 when process not found
     */
    public function test_show_returns_404_when_process_not_found(): void
    {
        $response = $this->actingAs($this->user)->get('/process/99999');
        $response->assertStatus(404);
    }

    /**
     * Test 404 when editing non-existent process
     */
    public function test_edit_returns_404_when_process_not_found(): void
    {
        $response = $this->actingAs($this->user)->get('/process/99999/edit');
        $response->assertStatus(404);
    }
}
