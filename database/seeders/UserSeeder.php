<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->times(50)
            ->create();

        $user = User::find(1);
        // Force one user to have the api_token we use in api calls
        $user->api_token = 'DmGIGjiJMtW2DHAqDiipKEU4Ql03t6ViRlhNECdTX26IPcIgeQsAFLUIfSFRsOS4K2Www9v4qmc5Cszs';
        $user->save();
    }
}
