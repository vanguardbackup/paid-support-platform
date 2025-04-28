<?php

use App\Console\Commands\PromoteUserCommand;
use App\Models\User;

it('can promote a user to staff', function () {
    $user = User::factory()->create();

    $this->artisan(PromoteUserCommand::class, ['userEmail' => $user->email])
        ->expectsOutputToContain('User now has staff privileges.');

    $this->assertTrue($user->fresh()->isStaff());
});

it('cannot promote a user that does not exist', function () {
    $this->artisan(PromoteUserCommand::class, ['userEmail' => 'nonexistentemail@email.com'])
        ->expectsOutputToContain('A user cannot be found with the email address provided. Please check the email address and try again.');

    $this->assertDatabaseMissing('users', [
        'email' => 'nonexistentemail@email.com']);

    $this->assertDatabaseCount('users', 0);
});

it('cannot promote a user that is already a staff member', function () {
    $user = User::factory()->staff()->create();

    $this->artisan(PromoteUserCommand::class, ['userEmail' => $user->email])
        ->expectsOutputToContain('User is already a member of staff.');

    $this->assertTrue($user->fresh()->isStaff());
});
