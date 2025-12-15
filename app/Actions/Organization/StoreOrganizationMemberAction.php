<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\DTOs\OrganizationMemberDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class StoreOrganizationMemberAction
{
    public function __construct() {}

    /**
     * Store an organization member
     * @param Organization $organization
     * @param OrganizationMemberDTO $dto
     * @return array
     */
    public function execute(Organization $organization, OrganizationMemberDTO $dto): array
    {
        return DB::transaction(function () use ($organization, $dto) {
            $user = \App\Models\User::where('email', $dto->email)->first();

            if (! $user) {
                // For now, we only support adding existing users.
                // In a real app, we might send an invitation email here.
                throw new \Exception('User not found.');
            }

            if ($organization->isMember($user)) {
                throw new \Exception('User is already a member.');
            }

            $organization->users()->attach($user->id, ['role' => $dto->role]);

            return ['success' => true];
        });
    }
}
