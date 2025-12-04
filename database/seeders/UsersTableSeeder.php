<?php

namespace Database\Seeders;

use App\Models\User;
use Bouncer;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use firstOrCreate to avoid duplicate key errors when seeding multiple times
        $user = User::firstOrCreate([
            'email' => 'luke@jedi.com',
        ], [
            'first_name' => 'Luke',
            'last_name' => 'Skywalker',
            'email_verified_at' => null,
            'password' => bcrypt('123123'),
        ]);

        Bouncer::assign('admin')->to($user);

        $others = User::factory(20)->create();
        foreach ($others as $model) {
            Bouncer::assign('regular')->to($model);
        }
    }
}
