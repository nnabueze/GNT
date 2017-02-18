<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class SubheadTableSeeder extends Seeder
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
       	        DB::table('subheads')->insert([
       	            'subhead_key' => str_random(15),
       	            'subhead_name' => $faker->name,
       	            'subhead_code' =>  str_random(4),
       	            'amount' => "2000",
       	        ]);
               }
    }
}
