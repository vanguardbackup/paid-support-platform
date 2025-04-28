<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSupportRequest;
use App\Mail\Support\NewRequestMail;
use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CreateSupportRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function show(): Response
    {
        return Inertia::render('support/create', [
            'availableCategories' => config('support-requests.available_categories'),
            'availableAssistanceTypes' => config('support-requests.available_assistance_types'),
        ]);
    }

    /**
     * Store a newly created support request in the database.
     */
    public function store(CreateSupportRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // This is only for a front-end checkbox, no database relationship.
        unset($validatedData['acceptTerms']);

        /** @var User $user */
        $user = Auth::user();

        $supportRequest = SupportRequest::create(array_merge($validatedData, [
            'user_id' => $user->id,
            'status' => SupportRequest::STATUS_OPEN,
            'ip_address' => request()->ip(),
        ]));

        $staffMembers = User::staff()->get();

        foreach ($staffMembers as $staffMember) {
            Mail::to($staffMember)->queue(new NewRequestMail($user, $staffMember, $supportRequest));
        }

        return Redirect::route('support.index');
    }
}
