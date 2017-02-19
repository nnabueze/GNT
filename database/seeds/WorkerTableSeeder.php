<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class WorkerTableSeeder extends Seeder
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
        	        DB::table('workers')->insert([
        	            'worker_key' => str_random(15),
        	            'worker_name' => $faker->name,
        	            'phone' =>  $faker->randomNumber(5),
        	            'email' => $faker->email,
        	            'pin' => str_random(4),
        	        ]);
                }
    }
}
