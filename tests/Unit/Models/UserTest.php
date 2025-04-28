<?php

use App\Models\User;

it('returns true if the user is a staff member', function () {
    $user = User::factory()->staff()->create();

    expect($user->isStaff())->toBeTrue();
});

it('returns true if the user is a staff member attribute', function () {
    $user = User::factory()->staff()->create();

    expect($user->is_staff_member)->toBeTrue();
});

it('returns false if the user is not a staff member', function () {
    $user = User::factory()->create();

    expect($user->isStaff())->toBeFalse();
});

it('returns false if the user is not a staff member attribute', function () {
    $user = User::factory()->create();

    expect($user->is_staff_member)->toBeFalse();
});

it('promotes a user to a staff member', function () {
    $user = User::factory()->create();

    expect($user->isStaff())->toBeFalse();

    $user->promoteToStaff();

    $user->refresh();

    expect($user->isStaff())->toBeTrue();
});

it('does not promote if they are already a staff member', function () {
    $user = User::factory()->staff()->create();

    expect($user->isStaff())->toBeTrue();

    $user->promoteToStaff();

    $user->refresh();

    expect($user->isStaff())->toBeTrue();
});

it('only returns staff members', function () {
    User::factory()->staff()->create(['name' => 'Staff User']);
    User::factory()->create(['name' => 'Regular User']);
    $staffUsers = User::staff()->get();

    expect($staffUsers)->toHaveCount(1)
        ->and($staffUsers->first()->isStaff())->toBeTrue()
        ->and($staffUsers->first()->name)->toBe('Staff User');
});
