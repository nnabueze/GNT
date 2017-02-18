<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

class MdaTableSeeder extends Seeder
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
        	        DB::table('mdas')->insert([
        	            'mda_key' => str_random(15),
        	            'mda_name' => $faker->name
        	        ]);
                }
    }
}
