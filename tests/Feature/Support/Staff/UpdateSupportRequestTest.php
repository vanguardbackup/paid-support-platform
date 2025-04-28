<?php

use App\Models\SupportRequest;
use App\Models\User;

test('A staff member can update a support request', function () {
    $user = User::factory()->staff()->create();
    $supportRequest = SupportRequest::factory()->create();

    $this->actingAs($user)
        ->post(route('support.update', ['supportRequest' => $supportRequest]), [
            'title' => 'Updated Title',
            'status' => 'in_progress',
            'staff_notes' => 'Updated notes',
            'assigned_staff_user_id' => $user->id,
        ]);

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'title' => 'Updated Title',
        'status' => 'in_progress',
        'staff_notes' => 'Updated notes',
        'assigned_staff_user_id' => $user->id,
    ]);
});

test('A regular user cannot update a support request', function () {
    $user = User::factory()->create();
    $supportRequest = SupportRequest::factory()->create();
    $originalTitle = $supportRequest->title;

    $response = $this->actingAs($user)
        ->post(route('support.update', ['supportRequest' => $supportRequest]), [
            'title' => 'Updated Title',
            'status' => 'in_progress',
            'staff_notes' => 'Updated notes',
            'assigned_staff_user_id' => $user->id,
        ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'title' => $originalTitle,
    ]);
});

test('An invalid support request cannot be updated', function () {
    $user = User::factory()->staff()->create();
    $nonExistentId = 99999; // Assuming this ID doesn't exist

    $response = $this->actingAs($user)
        ->post(route('support.update', ['supportRequest' => $nonExistentId]), [
            'title' => 'Updated Title',
            'status' => 'in_progress',
            'staff_notes' => 'Updated notes',
            'assigned_staff_user_id' => $user->id,
        ]);

    $response->assertNotFound();
});

test('A non staff member cannot be assigned the request', function () {
    $staffUser = User::factory()->staff()->create();
    $regularUser = User::factory()->create(); // Non-staff user
    $supportRequest = SupportRequest::factory()->create();

    $response = $this->actingAs($staffUser)
        ->post(route('support.update', ['supportRequest' => $supportRequest]), [
            'title' => 'Updated Title',
            'status' => 'in_progress',
            'staff_notes' => 'Updated notes',
            'assigned_staff_user_id' => $regularUser->id,
        ]);

    $response->assertSessionHasErrors(['assigned_staff_user_id']);

    $this->assertDatabaseMissing('support_requests', [
        'id' => $supportRequest->id,
        'assigned_staff_user_id' => $regularUser->id,
    ]);
});

test('A guest cannot update a support request', function () {
    $supportRequest = SupportRequest::factory()->create();
    $originalTitle = $supportRequest->title;

    $response = $this->post(route('support.update', ['supportRequest' => $supportRequest]), [
        'title' => 'Updated Title',
        'status' => 'in_progress',
        'staff_notes' => 'Updated notes',
    ]);

    $response->assertRedirect(route('login'));

    $this->assertDatabaseHas('support_requests', [
        'id' => $supportRequest->id,
        'title' => $originalTitle,
    ]);
});
