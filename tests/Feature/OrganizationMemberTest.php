<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_invite_member_by_email(): void
    {
        $admin = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $admin->id,
        ]);
        $organization->users()->attach($admin->id, ['role' => 'admin']);

        $member = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('organizations.invite', $organization), [
            'email' => $member->email,
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue($organization->isMember($member));
    }

    public function test_non_admin_cannot_invite_member(): void
    {
        $owner = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $owner->id,
        ]);
        $organization->users()->attach($owner->id, ['role' => 'admin']);

        $member = User::factory()->create();
        $organization->users()->attach($member->id, ['role' => 'member']);
        
        $outsider = User::factory()->create();

        $response = $this->actingAs($member)->post(route('organizations.invite', $organization), [
            'email' => $outsider->email,
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_switch_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Test Org',
            'user_id' => $user->id,
        ]);
        $organization->users()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('organizations.switch', $organization));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('organization_id', $organization->id);
    }

    public function test_user_cannot_switch_to_unauthorized_organization(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $organization = Organization::create([
            'name' => 'Other Org',
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->post(route('organizations.switch', $organization));

        $response->assertForbidden();
    }
}
