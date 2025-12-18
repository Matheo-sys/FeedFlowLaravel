<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DeleteUserAction
{
    /**
     * Delete the user account and handle session cleanup.
     *
     * @param Request $request
     * @return void
     */
    public function execute(Request $request): void
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
