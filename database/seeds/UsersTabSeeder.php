<?php

use Illuminate\Database\Seeder;

class UsersTabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                "name" => "saif$i",
                "email" => "saif$i@do.fr",
                "password" => bcrypt('0000')
            ]);
        }
    }
}
