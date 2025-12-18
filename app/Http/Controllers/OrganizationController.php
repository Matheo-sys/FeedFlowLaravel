<?php

namespace App\Http\Controllers;

use App\Actions\Organization\DeleteOrganizationAction;
use App\Actions\Organization\StoreOrganizationAction;
use App\Actions\Organization\UpdateOrganizationAction;
use App\DTOs\OrganizationDTO;
use App\Http\Requests\Organization\StoreOrganization;
use App\Http\Requests\Organization\UpdateOrganization;
use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\DTOs\OrganizationMemberDTO;
use App\Actions\Organization\StoreOrganizationMemberAction;
use App\Http\Requests\Organization\StoreOrganizationMember;
use App\Actions\Organization\SwitchOrganizationAction;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $organizations = auth()->user()->organizations;
        return view('organizations.index', compact('organizations'));
    }

    public function create(): View
    {
        return view('organizations.create');
    }

    public function store(StoreOrganization $request, StoreOrganizationAction $action): RedirectResponse
    {
        $dto = OrganizationDTO::fromRequest($request);
        $action->execute($dto);

        return redirect()->route('organizations.index')->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization): View
    {
        $this->authorize('update', $organization);
        return view('organizations.edit', compact('organization'));
    }

    public function update(UpdateOrganization $request, Organization $organization, UpdateOrganizationAction $action): RedirectResponse
    {
        $this->authorize('update', $organization);
        
        $dto = OrganizationDTO::fromRequest($request);
        $action->execute($organization, $dto);

        return redirect()->route('organizations.index')->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization, DeleteOrganizationAction $action): RedirectResponse
    {
        $this->authorize('delete', $organization);
        
        $action->execute($organization);

        return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully.');
    }

    public function invite(StoreOrganizationMember $request, Organization $organization, StoreOrganizationMemberAction $action): RedirectResponse
    {
        $this->authorize('update', $organization);

        try {
            $dto = OrganizationMemberDTO::fromRequest($request);
            $action->execute($organization, $dto);
            return back()->with('success', 'Member added successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function switchOrganization(Organization $organization, SwitchOrganizationAction $action): RedirectResponse
    {
        $this->authorize('view', $organization);
        
        $action->execute($organization);
        
        return redirect()->route('dashboard')->with('success', "Switched to {$organization->name}.");
    }
}
