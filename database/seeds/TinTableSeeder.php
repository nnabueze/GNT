<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class TinTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          	$faker = Faker::create();
          	foreach (range(1,3) as $index) {
      	        DB::table('tins')->insert([
      	            'tin_key' => str_random(15),
      	            'name' => $faker->name,
      	            'email' => $faker->email,
      	            'phone' => $faker->phoneNumber,
      	            'address' => $faker->address,
      	            'tin_no' => str_pad(rand(0,999), 11, "0", STR_PAD_LEFT),
      	            'temporary_tin' =>  str_pad(rand(0,999), 11, "0", STR_PAD_LEFT),
      	        ]);
              }
    }
}
