<?php

namespace App\Http\Controllers\Support\Staff;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CloseRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SupportRequest $supportRequest): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->isStaff(), 403, 'You do not have permission.');

        $supportRequest->closeRequest();

        return Redirect::route('support.show', $supportRequest);
    }
}
