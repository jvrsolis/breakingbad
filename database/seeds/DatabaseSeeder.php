<?php

use Illuminate\Database\Seeder;
use BreakingBad\Data\Lifecycle\CharacterLifecycle;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\User::class, 5)->create();
        $admin = factory(App\User::class, 1)->state('admin')->create();
        CharacterLifecycle::sync();
    }
}
