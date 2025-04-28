<?php

namespace App\Policies;

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupportRequestPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportRequest $supportRequest): Response
    {
        if ($user->is($supportRequest->user)) {
            return Response::allow();
        }

        if ($user->isStaff()) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to view this support request.');
    }
}
