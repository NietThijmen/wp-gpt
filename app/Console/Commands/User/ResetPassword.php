<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a users password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::get([
            'name',
            'email'
        ]);

        $userEmail = $this->choice(
            'Select the user to reset the password for',
            $users->pluck('email')->toArray()
        );

        $user = User::where('email', $userEmail)->first();
        if(!$user) {
            $this->error("User with email {$userEmail} not found.");
            return;
        }

        $newPassword = $this->secret("Enter the new password for user {$user->email}");

        $user->password = \Hash::make($newPassword);
        $user->save();

        $this->info("Password for user {$user->email} has been reset successfully.");
    }
}
