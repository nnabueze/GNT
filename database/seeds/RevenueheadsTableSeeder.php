<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class RevenueheadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
            	$faker = Faker::create();
            	foreach (range(1,3) as $index) {
        	        DB::table('revenueheads')->insert([
        	            'revenueheads_key' => str_random(15),
        	            'revenue_name' => $faker->name,
        	            'revenue_code' =>  str_random(4),
        	            'amount' => "2000",
        	        ]);
                }
    }
}
