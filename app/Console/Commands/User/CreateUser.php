<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->ask('Enter the username');
        $email = $this->ask('Enter the email address');
        $password = $this->secret('Enter the password');

        $user = \App\Models\User::create([
            'name' => $username,
            'email' => $email,
            'password' => \Hash::make($password),
        ]);

        $this->info("User {$user->email} has been created successfully.");
    }
}
