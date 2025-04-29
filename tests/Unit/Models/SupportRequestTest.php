<?php

use App\Models\SupportRequest;
use Carbon\Carbon;

test('closes a request', function () {
    $supportRequest = SupportRequest::factory([
        'status' => SupportRequest::STATUS_OPEN,
        'resolved_at' => null,
    ])->create();

    $supportRequest->closeRequest();

    $supportRequest->refresh();

    expect($supportRequest->status)->toBe(SupportRequest::STATUS_CLOSED)
        ->and($supportRequest->resolved_at)->toBeInstanceOf(Carbon::class);
});

test('it correctly calculates the support credits for a request', function () {
    Carbon::setTestNow(Carbon::createFromDate('2023-10-01 10:00:00'));

    // Create a support request with preferred time 1 hour from now
    $supportRequest = SupportRequest::factory([
        'status' => SupportRequest::STATUS_OPEN,
        'resolved_at' => null,
        'preferred_date' => now()->format('Y-m-d'), // Today
        'preferred_time' => '11:00', // One hour from frozen time
    ])->create();

    // Move time forward by 1 hour (to the preferred time)
    $this->travel(Carbon::now()->diffInHours($supportRequest->preferred_date.' '.$supportRequest->preferred_time))->hour();

    // Then move forward 1 more hour (this will make exactly 1 hour difference)
    $this->travel(Carbon::now()->diffInHours($supportRequest->preferred_date.' '.$supportRequest->preferred_time))->hour();

    // Now we're at 12:00, which is 1 hour after the preferred time
    $supportRequest->closeRequest();

    $supportRequest->refresh();

    // Should be exactly 1 credit (1 hour difference)
    expect($supportRequest->calculateCreditsOwed())->toBe(1)
        ->and($supportRequest->resolved_at)->toBeInstanceOf(Carbon::class);
});
