<?php
namespace App\Actions\Organization;

use App\DTOs\OrganizationDTO;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

final class DeleteOrganizationAction
{
    public function execute(Organization $organization): void
    {
        $organization->delete();
    }
}
