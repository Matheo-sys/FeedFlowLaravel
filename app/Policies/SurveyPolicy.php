<?php

namespace App\Policies;

use App\Models\Survey;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SurveyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $query = OrganizationUser::where('user_id',$user->id);
        if ($query) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survey $survey): bool
    {
        $query = OrganizationUser::where('user-id',$user->id)->where('organization_id',$survey->organization_id);
        if ($query || $survey->is_anonymous == true) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $query = OrganizationUser::where('user_id', $user->id)->first();
        if ($query) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survey $survey): bool
    {
        $query = OrganizationUser::where('user_id', $user->id)->where('organization_id', $survey->organization_id)->first();
        ;
        if ($user->id == $survey->user_id || $query->role == "admin") {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool
    {
        $query = OrganizationUser::where('user_id', $user->id)->where('organization_id', $survey->organization_id)->first();
        if ($user->id == $survey->user_id || $query->role == "admin") {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Survey $survey): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Survey $survey): bool
    {
        return false;
    }
}
