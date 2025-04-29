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

it('returns true if the user has negative credits', function () {
    $user = User::factory()->negativeCredits()->create();

    expect($user->hasNegativeCredits())->toBeTrue();
});

it('returns false if the user does not have negative credits', function () {
    $user = User::factory()->positiveCredits()->create();

    expect($user->hasNegativeCredits())->toBeFalse();
});

it('returns true if the user has no credits', function () {
    $user = User::factory()->create();

    expect($user->hasNoCredits())->toBeTrue();
});

it('returns false if the user has credits', function () {
    $user = User::factory()->positiveCredits()->create();

    expect($user->hasNoCredits())->toBeFalse();
});

it('returns true if the user has a negative credit balance', function () {
    $user = User::factory()->negativeCredits()->create();

    expect($user->hasNegativeCredits())->toBeTrue();
});

it('deducts credits from the users balance and makes it negative', function () {
    $user = User::factory()->create(); // 0 credits

    $user->deductCredits(10);

    expect($user->credit_balance)->toBe(-10);
});

it('deducts credits from the users balance', function () {
    $user = User::factory()->create(['credit_balance' => 10]);

    $user->deductCredits(5);

    expect($user->credit_balance)->toBe(5);
});
