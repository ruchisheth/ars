<?php

use Illuminate\Database\Seeder;

class KalanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('fieldreps')->where('approved_for_work',false)->where('initial_status', 1)->update(['initial_status' => '0']);
    	DB::table('fieldreps')->where('is_pending',true)->update(['approved_for_work' => NULL]);
    }
}
