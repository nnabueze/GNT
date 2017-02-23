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
        $this->call(IgrTableSeeder::class);
        $this->call(RevenueheadsTableSeeder::class);
        //$this->call(RemittancesTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
        $this->call(MdaTableSeeder::class);
        $this->call(SubheadTableSeeder::class);
        $this->call(WorkerTableSeeder::class);
        $this->call(StationTableSeeder::class);
        $this->call(PosTableSeeder::class);
    }
}
