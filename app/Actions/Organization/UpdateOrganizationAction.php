<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class UpdateOrganizationAction
{
    public function __construct() {}

    /**
     * Update an organization
     * @param Organization $organization
     * @param OrganizationDTO $dto
     * @return Organization
     */
    public function execute(Organization $organization, OrganizationDTO $dto): Organization
    {
        $organization->update([
            'name' => $dto->name,
        ]);

        return $organization;
    }
}
