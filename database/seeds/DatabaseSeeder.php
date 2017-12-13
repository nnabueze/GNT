<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //$this->call(IgrTableSeeder::class);
        DB::table('users')->insert([
            'name' => ucwords("Ercas Solution"),
            'email' => 'info@gmail.com',
            'password' => bcrypt('12345'),
            "api_token" => bin2hex(openssl_random_pseudo_bytes(30)),
        ]);

        DB::table('merchantid')->insert([
            'merchantId' => "NIBSS0000000045",
            'secretKey' => 'DD39CAB9976D86B31EB80B6F9560ABE0',
            'response_url'=> 'http://localhost:8000/pay/success',
            'cancel_url'=> 'http://localhost:8000/pay/testCancel',
        ]);
    }
}
