<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function api_can_create_a_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'API Test User',
            'email' => 'api@example.com',
            'phone_number' => '0123456789',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'status' => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'API Test User')
            ->assertJsonPath('data.email', 'api@example.com')
            ->assertJsonMissing(['password' => 'Password@123']);

        $this->assertDatabaseHas('users', [
            'email' => 'api@example.com',
            'phone_number' => '0123456789',
        ]);
    }

    #[Test]
    public function api_can_list_users_with_pagination(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    #[Test]
    public function api_can_filter_users_by_status(): void
    {
        User::factory()->create([
            'name' => 'Active User',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Inactive User',
            'status' => 'inactive',
        ]);

        $response = $this->getJson('/api/users?status=inactive');

        $response->assertOk()
            ->assertJsonFragment([
                'name' => 'Inactive User',
                'status' => 'inactive',
            ])
            ->assertJsonMissing([
                'name' => 'Active User',
            ]);
    }

    #[Test]
    public function api_can_show_a_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Detail User',
        ]);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', 'Detail User')
            ->assertJsonMissing(['password']);
    }

    #[Test]
    public function api_can_soft_delete_a_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function api_can_bulk_delete_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->deleteJson('/api/users/bulk', [
            'user_ids' => [$user1->id, $user2->id],
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Selected users deleted successfully.',
            ]);

        $this->assertSoftDeleted('users', [
            'id' => $user1->id,
        ]);

        $this->assertSoftDeleted('users', [
            'id' => $user2->id,
        ]);
    }

    #[Test]
    public function api_rejects_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/users', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'phone_number' => '0199999999',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'status' => 'active',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }
}