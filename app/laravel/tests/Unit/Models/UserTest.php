<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fillable attributes
     *
     * @return void
     */
    public function test_fillable_attributes()
    {
        $fillable = [
            'name',
            'email',
            'password',
            'role',
        ];

        $user = new User;
        $this->assertEquals($fillable, $user->getFillable());
    }

    /**
     * Test hidden attributes
     *
     * @return void
     */
    public function test_hidden_attributes()
    {
        $hidden = [
            'password',
            'remember_token',
        ];

        $user = new User;
        $this->assertEquals($hidden, $user->getHidden());
    }

    /**
     * Test password is hashed when set
     *
     * @return void
     */
    public function test_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'password123'
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    /**
     * Test user can be created with factory
     *
     * @return void
     */
    public function test_user_can_be_created_with_factory()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'email' => $user->email
        ]);
    }

    /**
     * Test email verification timestamp cast
     *
     * @return void
     */
    public function test_email_verified_at_is_cast_to_datetime()
    {
        $user = new User;
        $casts = $user->getCasts();

        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
    }

    /**
     * Test user role attribute
     *
     * @return void
     */
    public function test_user_role_attribute()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $this->assertEquals('admin', $user->role);
    }

    /**
     * Test user name attribute
     *
     * @return void
     */
    public function test_user_name_attribute()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        
        $this->assertEquals('Test User', $user->name);
    }
}