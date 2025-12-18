<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\DTOs\ProfileDTO;
use App\Actions\Profile\UpdateProfileAction;
use App\Http\Requests\Profile\DeleteAccountRequest;
use App\Actions\Profile\DeleteUserAction;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, UpdateProfileAction $action): RedirectResponse
    {
        $dto = ProfileDTO::fromRequest($request);
        $action->execute($request->user(), $dto);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(DeleteAccountRequest $request, DeleteUserAction $action): RedirectResponse
    {
        $action->execute($request);

        return Redirect::to('/');
    }
}
