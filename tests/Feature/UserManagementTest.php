<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
{
    /** @var User $admin */
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    return $admin;
}

    /** @test */
    public function admin_can_view_user_dashboard(): void
    {
       $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_a_user(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone_number' => '0123456789',
                'password' => 'Password@123',
                'password_confirmation' => 'Password@123',
                'status' => 'active',
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '0123456789',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function duplicate_email_is_rejected(): void
    {
        $admin = $this->createAdmin();

        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Another User',
                'email' => 'existing@example.com',
                'phone_number' => '0198765432',
                'password' => 'Password@123',
                'password_confirmation' => 'Password@123',
                'status' => 'active',
            ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function admin_can_update_a_user(): void
    {
        $admin = $this->createAdmin();

        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name' => 'Updated Name',
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'password' => '',
                'password_confirmation' => '',
                'status' => 'inactive',
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'status' => 'inactive',
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_a_user(): void
    {
        $admin = $this->createAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function admin_can_bulk_delete_users(): void
    {
        $admin = $this->createAdmin();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.users.bulk-destroy'), [
                'user_ids' => [$user1->id, $user2->id],
            ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertSoftDeleted('users', [
            'id' => $user1->id,
        ]);

        $this->assertSoftDeleted('users', [
            'id' => $user2->id,
        ]);
    }

    /** @test */
    public function users_can_be_filtered_by_status(): void
    {
        $admin = $this->createAdmin();

        User::factory()->create([
            'name' => 'Active User',
            'status' => 'active',
        ]);

        User::factory()->create([
            'name' => 'Inactive User',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard', [
                'status' => 'inactive',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Inactive User');
        $response->assertDontSee('Active User');
    }

    /** @test */
    public function unauthenticated_users_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }


    /** @test */
    public function non_admin_users_cannot_access_admin_dashboard(): void
    {
    /** @var User $user */
    $user = User::factory()->create([
        'is_admin' => false,
    ]);

    $response = $this->actingAs($user)
        ->get(route('admin.dashboard'));

    $response->assertForbidden();
    }
}