<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{

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
            'email_verified_at',
            'created_at',
            'updated_at',
        ];

        $user = new User;
        $this->assertEquals($hidden, $user->getHidden());
    }

    /**
     * Test role cast to enum
     *
     * @return void
     */
    public function test_role_is_cast_to_enum()
    {
        $user = new User;
        $casts = $user->getCasts();

        $this->assertArrayHasKey('role', $casts);
        $this->assertEquals(\App\Enums\RoleType::class, $casts['role']);
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

}