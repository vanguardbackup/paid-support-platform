<?php

use App\Models\SupportRequest;
use App\Models\User;

test('a staff member can close a support request', function () {
    $staff = User::factory()->staff()->create();

    $supportRequest = SupportRequest::factory()->create([
        'status' => SupportRequest::STATUS_OPEN,
    ]);

    $this->actingAs($staff)
        ->post(route('support.close', $supportRequest))
        ->assertRedirect(route('support.show', $supportRequest));

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'status' => SupportRequest::STATUS_CLOSED,
    ]);
});

test('a user cannot close a support request', function () {
    $user = User::factory()->create();

    $supportRequest = SupportRequest::factory()->create([
        'status' => SupportRequest::STATUS_OPEN,
    ]);

    $this->actingAs($user)
        ->post(route('support.close', $supportRequest))
        ->assertForbidden();

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'status' => SupportRequest::STATUS_OPEN,
    ]);
});

test('a guest cannot close a support request', function () {
    $supportRequest = SupportRequest::factory()->create([
        'status' => SupportRequest::STATUS_OPEN,
    ]);

    $this->post(route('support.close', $supportRequest))
        ->assertRedirect(route('login'));

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'status' => SupportRequest::STATUS_OPEN,
    ]);
});

test('a non-existent support request cannot be closed', function () {
    $staff = User::factory()->staff()->create();

    $supportRequest = SupportRequest::factory()->create([
        'status' => SupportRequest::STATUS_OPEN,
    ]);

    $this->actingAs($staff)
        ->post(route('support.close', 999))
        ->assertNotFound();

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'status' => SupportRequest::STATUS_OPEN,
    ]);
});
