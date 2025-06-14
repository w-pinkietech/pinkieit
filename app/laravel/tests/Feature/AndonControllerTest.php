<?php

namespace Tests\Feature;

use App\Models\Process;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AndonControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test home page redirects to login when unauthenticated
     *
     * @return void
     */
    public function test_home_page_redirects_when_unauthenticated()
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
    }

    /**
     * Test home page is accessible when authenticated
     *
     * @return void
     */
    public function test_home_page_accessible_when_authenticated()
    {
        $response = $this->actingAs($this->user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas(['processes', 'config']);
    }

    /**
     * Test root path redirects to home
     *
     * @return void
     */
    public function test_root_redirects_to_home()
    {
        $response = $this->get('/');

        $response->assertRedirect('/home');
    }

    /**
     * Test andon edit page requires authentication
     *
     * @return void
     */
    public function test_andon_edit_requires_authentication()
    {
        $response = $this->get('/home/edit');

        $response->assertRedirect('/login');
    }

    /**
     * Test andon edit page is accessible when authenticated
     *
     * @return void
     */
    public function test_andon_edit_accessible_when_authenticated()
    {
        $response = $this->actingAs($this->user)->get('/home/edit');

        $response->assertStatus(200);
        $response->assertViewIs('andon.config');
        $response->assertViewHas(['processes', 'config', 'columns', 'easing']);
    }

    /**
     * Test andon configuration update with valid data
     *
     * @return void
     */
    public function test_andon_update_with_valid_data()
    {
        $validData = [
            'columns' => 4,
            'easing' => 'ease-in-out',
            'duration' => 5,
            'processes' => [],
        ];

        $response = $this->actingAs($this->user)->put('/home', $validData);

        $response->assertRedirect('/home');
        $response->assertSessionHas('success');
    }

    /**
     * Test andon configuration update with invalid data
     *
     * @return void
     */
    public function test_andon_update_with_invalid_data()
    {
        $invalidData = [
            'columns' => 'invalid',
            'easing' => '',
            'duration' => -1,
        ];

        $response = $this->actingAs($this->user)->put('/home', $invalidData);

        $response->assertSessionHasErrors(['columns', 'easing', 'duration']);
    }

    /**
     * Test andon update requires authentication
     *
     * @return void
     */
    public function test_andon_update_requires_authentication()
    {
        $response = $this->put('/home', []);

        $response->assertRedirect('/login');
    }

    /**
     * Test home page displays processes data
     *
     * @return void
     */
    public function test_home_page_displays_processes_data()
    {
        $process = Process::factory()->create(['name' => 'Test Process']);

        $response = $this->actingAs($this->user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Test Process');
    }

    /**
     * Test andon configuration page displays form elements
     *
     * @return void
     */
    public function test_andon_config_page_displays_form_elements()
    {
        $response = $this->actingAs($this->user)->get('/home/edit');

        $response->assertStatus(200);
        $response->assertSee('columns');
        $response->assertSee('easing');
        $response->assertSee('duration');
    }
}
