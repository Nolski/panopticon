<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAllPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $confirmed = $this->confirm('Do you really wish to run this command?');

        if (! $confirmed) {
            $this->comment('Command Cancelled!');

            return false;
        }

        $password = $this->ask('What is the password?', 'irc1234!');

        foreach(User::all() as $user){
            $user->password = Hash::make($password);
            $user->save();
            $this->info('Set password for ' . $user->name);
        }

    }
}
