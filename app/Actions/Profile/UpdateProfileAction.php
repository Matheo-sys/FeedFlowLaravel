<?php

namespace App\Actions\Profile;

use App\DTOs\ProfileDTO;
use App\Models\User;

final class UpdateProfileAction
{
    /**
     * Update the user's profile information.
     *
     * @param User $user
     * @param ProfileDTO $dto
     * @return void
     */
    public function execute(User $user, ProfileDTO $dto): void
    {
        $user->fill([
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'email' => $dto->email,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
