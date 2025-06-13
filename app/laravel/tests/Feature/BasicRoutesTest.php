<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicRoutesTest extends TestCase
{
    /**
     * Test root redirects to home
     *
     * @return void
     */
    public function test_root_redirects_to_home()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }

    /**
     * Test home redirects when not authenticated
     *
     * @return void
     */
    public function test_home_redirects_when_not_authenticated()
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test login page is accessible
     *
     * @return void
     */
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('ログイン'); // Japanese text for Login
    }

    /**
     * Test server date endpoint requires authentication
     *
     * @return void
     */
    public function test_server_date_endpoint_requires_authentication()
    {
        $response = $this->get('/date');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}