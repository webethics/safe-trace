<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [[
            'id'         => 1,
            'title'      => 'Data Provider Admin',
            'slug'      => 'data_admin',
            'group_id'      => 2,
            'created_at' => '2019-04-15 19:13:32',
            'updated_at' => '2019-04-15 19:13:32',
            'deleted_at' => null,
        ], 
          [
                'id'         => 2,
                'title'      => 'Data Provider Analyst',
                'slug'      => 'data_analyst',
				'group_id'      => 2,
                'created_at' => '2019-04-15 19:13:32',
                'updated_at' => '2019-04-15 19:13:32',
                'deleted_at' => null,
            ],
            [
                'id'         =>3,
                'title'      => 'Customer Admin',
                'slug'      => 'customer_admin',
				'group_id'      => 1,
                'created_at' => '2019-04-15 19:13:32',
                'updated_at' => '2019-04-15 19:13:32',
                'deleted_at' => null,
            ],
			[
                'id'         => 4,
                'title'      => 'Customer User',
                'slug'      => 'customer_user',
				'group_id'      => 1,
                'created_at' => '2019-04-15 19:13:32',
                'updated_at' => '2019-04-15 19:13:32',
                'deleted_at' => null,
            ]];

        Role::insert($roles);
    }
}