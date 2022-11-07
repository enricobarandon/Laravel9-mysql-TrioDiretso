<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserType;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'uuid'              =>      (string) Str::orderedUuid(),
            'first_name'        =>      'Foo',
            'middle_name'       =>      'D',
            'last_name'         =>      'Bar',
            'username'          =>      'kikoadmin',
            'email'             =>      'kiko@lucky8',
            'user_type_id'      =>      1
        ]);

        \App\Models\User::factory()->create([
            'uuid'              =>      (string) Str::orderedUuid(),
            'first_name'        =>      'Admin',
            'middle_name'       =>      'D',
            'last_name'         =>      'Admin',
            'username'          =>      'admin',
            'email'             =>      'admin@lucky8',
            'user_type_id'      =>      1
        ]);

        \App\Models\User::factory()->create([
            'uuid'              =>      (string) Str::orderedUuid(),
            'first_name'        =>      'Cashier',
            'middle_name'       =>      'And',
            'last_name'         =>      'Teller',
            'username'          =>      'cashier',
            'email'             =>      'cashierteller@lucky8',
            'user_type_id'      =>      2
        ]);

        $this->call(UserTypeSeeder::class);
    }
}
