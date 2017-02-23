<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class PosTableSeeder extends Seeder
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
        	        DB::table('postables')->insert([
        	            'pos_key' => str_random(15),
        	            'pos_imei' => "1122334455",
        	            'name' => $faker->name,
        	            'activation_code' =>  str_random(4),
        	        ]);
                }
    }
}
