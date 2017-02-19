<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class RemittancesTableSeeder extends Seeder
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
        	        DB::table('remittances')->insert([
        	            'remittance_key' => str_random(15),
        	            'name' => $faker->name,
        	            'email' => $faker->email,
        	            'phone' => "08032746783",
        	            'amount' => '1000',
        	            'start_date' =>  "2016-09-14 09:21:26",
        	            'end_date' =>  "2016-09-14 09:21:26"
        	        ]);
                }
    }
}
