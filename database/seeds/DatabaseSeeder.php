<?php

use App\tbl_user;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        tbl_user::create([
            'mobile_no'   => '1111111111',
            'name'        => 'Supr User',
            'designation' => 'Super User',
            'user_type'   => 0,
        ]);
    }
}
