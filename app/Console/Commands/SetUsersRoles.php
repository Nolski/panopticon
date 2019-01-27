<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetUsersRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:users-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset users roles from excel sheet.';

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
        $path = Storage::disk('local')->path('field-team.csv');

        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $username =  strtolower($data[2]);

                $eso = 3;
                $esso = 4;

                $role = $data[1];

                $user = User::where('commcare_username', 'like' , '%' . $username . '%')->first();

                if(!$user){
                    $this->error("Not found " . $username);
                    continue;
                }

                if($role === 'ESO'){
                    $user->roles()->sync([$eso]);
                }else{
                    $user->roles()->sync([$esso]);
                }


            }
            fclose($handle);
        }
    }
}
