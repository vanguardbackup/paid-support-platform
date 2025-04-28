<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ViewRequestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SupportRequest $supportRequest): Response
    {
        Gate::authorize('view', $supportRequest);

        /** @var User $user */
        $user = Auth::user();

        return Inertia::render('support/view', [
            'timeInStaffTimezone' => $this->convertPreferredTimeToStaffTimezone($user, $supportRequest),
            'supportRequest' => array_merge(
                $supportRequest->toArray(),
                $user->isStaff() ? [
                    'staff_notes' => $supportRequest->staff_notes,
                ] : [],
                ['submitter' => $supportRequest->user->only(['name', 'email'])]
            ),
            ...($user->isStaff() ? [
                'availableStaffMembers' => User::staff()
                    ->get(['id', 'name', 'email'])
                    ->map(fn ($staff) => [
                        'id' => $staff->getKey(),
                        'name' => $staff->getAttribute('name'),
                        'avatar' => $staff->getAttribute('avatar'),
                    ])
                    ->values(),
            ] : []),
        ]);
    }

    /**
     *  Convert the preferred date to the authenticated user's timezone.
     */
    private function convertPreferredTimeToStaffTimezone(User $user, SupportRequest $supportRequest): ?string
    {
        if (! $user->timezone || ! $supportRequest->timezone) {
            return null;
        }

        $submitterTimezone = $this->convertOffsetToTimezone($supportRequest->timezone);
        if (! $submitterTimezone) {
            Log::warning('Unsupported timezone offset: '.$supportRequest->timezone);

            return null;
        }

        try {
            $submitterTime = new DateTime($supportRequest->preferred_time, new DateTimeZone($submitterTimezone));
            $submitterTime->setTimezone(new DateTimeZone($user->timezone));

            return $submitterTime->format('g:i A');
        } catch (Exception $e) {
            Log::error('Error converting preferred time to staff timezone: '.$e->getMessage(), [
                'support_request_id' => $supportRequest->id,
                'user_id' => $user->id,
            ]);

            return null;
        }
    }

    private function convertOffsetToTimezone(string $offset): ?string
    {
        $map = [
            'UTC-12:00' => 'Etc/GMT+12',
            'UTC-11:00' => 'Etc/GMT+11',
            'UTC-10:00' => 'Etc/GMT+10',
            'UTC-09:00' => 'Etc/GMT+9',
            'UTC-08:00' => 'Etc/GMT+8',
            'UTC-07:00' => 'Etc/GMT+7',
            'UTC-06:00' => 'Etc/GMT+6',
            'UTC-05:00' => 'Etc/GMT+5',
            'UTC-04:00' => 'Etc/GMT+4',
            'UTC-03:00' => 'Etc/GMT+3',
            'UTC-02:00' => 'Etc/GMT+2',
            'UTC-01:00' => 'Etc/GMT+1',
            'UTC+00:00' => 'Etc/GMT',
            'UTC+01:00' => 'Etc/GMT-1',
            'UTC+02:00' => 'Etc/GMT-2',
            'UTC+03:00' => 'Etc/GMT-3',
            'UTC+04:00' => 'Etc/GMT-4',
            'UTC+05:00' => 'Etc/GMT-5',
            'UTC+06:00' => 'Etc/GMT-6',
            'UTC+07:00' => 'Etc/GMT-7',
            'UTC+08:00' => 'Etc/GMT-8',
            'UTC+09:00' => 'Etc/GMT-9',
            'UTC+10:00' => 'Etc/GMT-10',
            'UTC+11:00' => 'Etc/GMT-11',
            'UTC+12:00' => 'Etc/GMT-12',
        ];

        return $map[$offset] ?? null;
    }
}
