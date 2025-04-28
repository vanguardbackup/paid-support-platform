<?php

use App\Mail\Support\NewRequestMail;
use App\Models\SupportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

function validSupportFormData(array $overrides = []): array
{
    return array_merge([
        'title' => 'My Support Request',
        'category' => 'installation',
        'preferred_assistance_type' => 'email',
        'preferred_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
        'preferred_time' => '10:00',
        'timezone' => 'UTC-01:00',
        'additional_details' => 'Please assist me with the installation, it isn\'t working and I desperately need help! Thank you.',
        'acceptTerms' => true,
    ], $overrides);
}

test('A regular user can submit a support request', function () {
    Mail::fake();
    $staffUser = User::factory()->staff()->create();
    $user = User::factory()->create();

    $request = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData());

    $request->assertStatus(302);
    $request->assertRedirect(route('support.index'));
    $this->assertAuthenticatedAs($user);

    $this->assertDatabaseHas('support_requests', [
        'status' => SupportRequest::STATUS_OPEN,
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'title' => 'My Support Request',
        'category' => 'installation',
        'preferred_assistance_type' => 'email',
        'preferred_date' => Carbon::now()->addDays(2)->format('Y-m-d').' 00:00:00',
        'preferred_time' => '10:00',
        'timezone' => 'UTC-01:00',
        'additional_details' => 'Please assist me with the installation, it isn\'t working and I desperately need help! Thank you.',
    ]);

    $supportRequest = SupportRequest::first();

    Mail::assertQueued(NewRequestMail::class, function ($mail) use ($user, $staffUser) {
        return $mail->hasTo($staffUser->email) &&
            $mail->requester->is($user) &&
            $mail->supportRequest->user_id === $user->id;
    });
});

test('the timezone must be valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'timezone' => 'Invalid/Timezone',
        ]));

    $response->assertSessionHasErrors('timezone');
});

test('a time cannot fall outside of regular working hours', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'preferred_time' => '03:00',
        ]));

    $response->assertSessionHasErrors('preferred_time');
});

test('a category must be valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'category' => 'not-a-real-category',
        ]));

    $response->assertSessionHasErrors('category');
});

test('an assistance type must be valid', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'preferred_assistance_type' => 'carrier-pigeon',
        ]));

    $response->assertSessionHasErrors('preferred_assistance_type');
});

test('you cannot set a date in the past', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'preferred_date' => Carbon::now()->subDay()->format('Y-m-d'),
        ]));

    $response->assertSessionHasErrors('preferred_date');
});

test('you cannot set a date more than 3 months in the future', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'preferred_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
        ]));

    $response->assertSessionHasErrors('preferred_date');
});

test('a guest cannot create a support ticket', function () {
    $response = $this->post(route('support.store'), validSupportFormData());

    $response->assertRedirect(route('login'));
});

test('you cannot submit without accepting the terms', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('support.store'), validSupportFormData([
            'acceptTerms' => false,
        ]));

    $response->assertSessionHasErrors('acceptTerms');
});

test('a user can render the form page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('support.create'));

    $response->assertStatus(200);
});

test('a guest cannot render the form page', function () {
    $response = $this->get(route('support.create'));

    $response->assertRedirect(route('login'));
});
