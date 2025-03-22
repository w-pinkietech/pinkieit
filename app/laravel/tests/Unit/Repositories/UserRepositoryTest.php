<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase\RepositoryTestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Enums\RoleType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserRepositoryTest extends RepositoryTestCase
{
    use WithFaker;

    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    public function test_can_create_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => RoleType::User,
        ];

        $user = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['role'], $user->role);
        $this->assertTrue(Hash::check($data['password'], $user->password));
    }

    public function test_can_find_user_by_id()
    {
        $user = User::factory()->create();

        $found = $this->repository->find($user->id);

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create([
            'name' => 'Old Name'
        ]);

        $updated = $this->repository->update($user->id, [
            'name' => 'New Name'
        ]);

        $this->assertEquals('New Name', $updated->name);
    }

    public function test_can_find_user_by_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $found = $this->repository->findByEmail('test@example.com');

        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->id, $found->id);
    }

    public function test_can_get_users_by_role()
    {
        User::factory()->count(2)->create([
            'role' => RoleType::Admin
        ]);
        User::factory()->create([
            'role' => RoleType::User
        ]);

        $admins = $this->repository->getByRole(RoleType::Admin);

        $this->assertCount(2, $admins);
        $this->assertTrue($admins->every(fn($user) => $user->role === RoleType::Admin));
    }
}
