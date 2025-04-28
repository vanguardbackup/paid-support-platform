<?php

use App\Models\User;

test('a user can render the page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('support.index'));

    $response->assertStatus(200);
});

test('a guest cannot render the page', function () {
    $response = $this->get(route('support.index'));

    $response->assertRedirect(route('login'));
});
