<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AndonControllerTest extends TestCase
{
    use RefreshDatabase;
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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('home');
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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/home/edit');

        $response->assertStatus(200);
        $response->assertViewIs('andon.config');
    }
}
