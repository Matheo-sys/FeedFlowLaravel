<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use App\Models\User;

final class StoreOrganizationAction
{
    public function __construct() {}

    /**
     * Store an organization
     * @param OrganizationDTO $dto
     * @return Organization
     */
    public function execute(OrganizationDTO $dto): Organization
    {
        return DB::transaction(function () use ($dto) {
            $organization = Organization::create([
                'name' => $dto->name,
                'user_id' => $dto->owner_id,
            ]);

            $organization->users()->attach($dto->owner_id, ['role' => 'admin']);

            User::where('id', $dto->owner_id)->update(['organization_id' => $organization->id]);

            return $organization;
        });
    }
}
