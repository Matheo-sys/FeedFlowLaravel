<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\DTOs\OrganizationMemberDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use App\Models\OrganizationUser;
use \App\Models\User;

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
            $user = User::where('email', $dto->email)->first();

            if (! $user) {
                throw new \Exception('User not found.');
            }

            if ($organization->isMember($user)) {
                throw new \Exception('User is already a member.');
            }

            OrganizationUser::create([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'role' => $dto->role,
            ]);

            return ['success' => true];
        });
    }
}
