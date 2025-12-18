<?php

namespace App\Actions\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class SwitchOrganizationAction
{
    /**
     * Switch the current organization for the user.
     *
     * @param Organization $organization
     * @return void
     */
    public function execute(Organization $organization): void
    {
        /** @var User $user */
        $user = Auth::user();

        // Update the user's active organization ID in the database
        $user->update(['organization_id' => $organization->id]);

        // Update the organization ID in the session
        session(['organization_id' => $organization->id]);
    }
}
