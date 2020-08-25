<?php

use App\Models\CdrGroup;
use Illuminate\Database\Seeder;

class CdrGroupTableSeeder  extends Seeder
{
    public function run()
    {
        $users = [
		[
            'id'             => 1,
            'group_name'=>'Customer',
          
        ],[
            'id'             => 2,
            'group_name'=>'Data Provider',
          
        ]];

        CdrGroup::insert($users);
    }
}
