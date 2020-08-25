<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
		[
            'id'             => 1,
            'first_name'           => 'Super',
            'last_name'           => 'Admin',
            'role_id'           => 1,
            'email'          => 'super_admin@super_admin.com',
            'otp'          => '123456',
			'mobile_number'      =>'3434343234',
			'status' =>1,
			'created_by'      =>1,
            'password'       => '$2y$10$iy9bIWCegF2PjbVD9wiQzux0upYA.A1UBR8KbVR2QS.rDC6d0NLjO',
            'remember_token' => null,
            'created_at'     => '2019-11-15 19:13:32',
            'updated_at'     => '2019-11-15 19:13:32',
            'deleted_at'     => null,
        ],[
            'id'             => 2,
            'first_name'           => 'Data',
            'last_name'           => 'Admin',
            'role_id'           => 2,
            'email'          => 'data_admin@data_admin.com',
            'otp'          => '123456',
			'mobile_number'      =>'3434343234',
			'created_by'      =>1,
			'status' =>1,
            'password'       => '$2y$10$iy9bIWCegF2PjbVD9wiQzux0upYA.A1UBR8KbVR2QS.rDC6d0NLjO',
            'remember_token' => null,
            'created_at'     => '2019-11-15 19:13:32',
            'updated_at'     => '2019-11-15 19:13:32',
            'deleted_at'     => null,
        ],
		[
            'id'             => 3,
            'first_name'           => 'Data Analyst',
            'last_name'           => '',
            'role_id'           => 3,
            'email'          => 'data_analyst@data_analyst.com',
			'otp'          => '123456',
			'created_by'      =>1,
			'mobile_number'      =>'3434343234',
			'status' =>1,
            'password'       => '$2y$10$iy9bIWCegF2PjbVD9wiQzux0upYA.A1UBR8KbVR2QS.rDC6d0NLjO',
            'remember_token' => null,
            'created_at'     => '2019-11-15 19:13:32',
            'updated_at'     => '2019-11-15 19:13:32',
            'deleted_at'     => null,
        ],
		[
            'id'             => 4,
            'first_name'           => 'Customer',
            'last_name'           => 'Admin',
            'role_id'           => 4,
            'email'          => 'customer_admin@customer_admin.com',
			'otp'          => '123456',
			'mobile_number'      =>'3434343234',
			'status' =>1,
			'created_by'      =>1,
            'password'       => '$2y$10$iy9bIWCegF2PjbVD9wiQzux0upYA.A1UBR8KbVR2QS.rDC6d0NLjO',
            'remember_token' => null,
            'created_at'     => '2019-11-15 19:13:32',
            'updated_at'     => '2019-11-15 19:13:32',
            'deleted_at'     => null,
        ],
		[
            'id'             => 5,
            'first_name'           => 'Customer User',
            'last_name'           => '',
            'role_id'           => 5,
            'email'          => 'customer_user@customer_user.com',
			'otp'          => '123456',
			'mobile_number'      =>'3434343234',
			'status' =>1,
			'created_by'      =>1,
            'password'       => '$2y$10$iy9bIWCegF2PjbVD9wiQzux0upYA.A1UBR8KbVR2QS.rDC6d0NLjO',
            'remember_token' => null,
            'created_at'     => '2019-11-15 19:13:32',
            'updated_at'     => '2019-11-15 19:13:32',
            'deleted_at'     => null,
        ]];

        User::insert($users);
    }
}
