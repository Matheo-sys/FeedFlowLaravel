<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_organization_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('organizations.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_organization(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organizations.store'), [
            'name' => 'New Organization',
        ]);

        $response->assertRedirect(route('organizations.index'));
        $this->assertDatabaseHas('organizations', [
            'name' => 'New Organization',
            'user_id' => $user->id,
        ]);
        
        $organization = Organization::where('name', 'New Organization')->first();
        $this->assertTrue($organization->isAdmin($user));
    }

    public function test_user_can_update_their_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Old Name',
            'user_id' => $user->id,
        ]);
        $organization->users()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->put(route('organizations.update', $organization), [
            'name' => 'New Name',
        ]);

        $response->assertRedirect(route('organizations.index'));
        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'name' => 'New Name',
        ]);
    }

    public function test_user_cannot_update_others_organization(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Other Org',
            'user_id' => $otherUser->id,
        ]);
        $organization->users()->attach($otherUser->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->put(route('organizations.update', $organization), [
            'name' => 'Hacked Name',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_their_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'To Delete',
            'user_id' => $user->id,
        ]);
        $organization->users()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->delete(route('organizations.destroy', $organization));

        $response->assertRedirect(route('organizations.index'));
        $this->assertDatabaseMissing('organizations', [
            'id' => $organization->id,
        ]);
    }
}
