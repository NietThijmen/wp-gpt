<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user';

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
            'Select the user to delete',
            $users->pluck('email')->toArray()
        );

        $user = User::where('email', $userEmail)->first();
        if(!$user) {
            $this->error("User with email {$userEmail} not found.");
            return;
        }

        if ($this->confirm("Are you sure you want to delete user {$user->email}? This action cannot be undone.")) {
            $user->delete();
            $this->info("User {$user->email} has been deleted.");
        } else {
            $this->info('User deletion cancelled.');
        }
    }
}
