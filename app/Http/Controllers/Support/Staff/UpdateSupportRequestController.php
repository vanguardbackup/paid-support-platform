<?php

namespace App\Http\Controllers\Support\Staff;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UpdateSupportRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SupportRequest $supportRequest): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->isStaff(), 403, 'You do not have permission.');

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:open,in_progress'],
            'staff_notes' => ['nullable', 'string'],
            'assigned_staff_user_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value && ! User::find($value)->isStaff()) {
                        $fail('The selected user is not a valid staff member.');
                    }
                },
            ],
        ]);

        $supportRequest->update([
            'title' => $request->input('title'),
            'status' => $request->input('status'),
            'assigned_staff_user_id' => $request->input('assigned_staff_user_id'),
            'staff_notes' => $request->input('staff_notes'),
        ]);

        $supportRequest->save();

        return Redirect::route('support.show', $supportRequest);
    }
}
