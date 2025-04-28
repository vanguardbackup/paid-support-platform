<?php

use App\Models\SupportRequest;
use App\Models\User;

test('the creator can render the page', function () {
    $user = User::factory()->create();
    $supportRequest = SupportRequest::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('support.show', $supportRequest));

    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

test('a staff member can render the page', function () {
    $staffMember = User::factory()->staff()->create();
    $supportRequest = SupportRequest::factory()->create();

    $response = $this->actingAs($staffMember)
        ->get(route('support.show', $supportRequest));

    $response->assertStatus(200);
    $this->assertAuthenticatedAs($staffMember);
});

test('the non-creator cannot render the view', function () {
    $user = User::factory()->create();
    $supportRequest = SupportRequest::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('support.show', $supportRequest));

    $response->assertStatus(403);
    $this->assertAuthenticatedAs($user);
});

test('a guest cannot render the view', function () {
    $response = $this->get(route('support.index'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});
