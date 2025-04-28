<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psp:promote-user {userEmail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promotes a user to a staff member.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $userEmailAddress = $this->argument('userEmail');

        $user = User::where('email', $userEmailAddress)->first();

        if (! $user) {
            $this->components->error('A user cannot be found with the email address provided. Please check the email address and try again.');

            return;
        }

        if ($user->isStaff()) {
            $this->components->error('User is already a member of staff.');

            return;
        }

        $user->promoteToStaff();

        $this->components->success('User now has staff privileges.');
    }
}
