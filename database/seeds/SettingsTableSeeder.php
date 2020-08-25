<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
		[
            'id'             => 1,
            'double_authentication'=>1,
            'smtp_host'=>'mail.mgdsw.info',
            'smtp_port'=>'mail.mgdsw.info',
            'smtp_user'=>'mail.mgdsw.info',
            'smtp_password'=>'mail.mgdsw.info',
            'from_name'=>'mail.mgdsw.info',
            'from_email'=>'mail.mgdsw.info',
            'site_title'=>'CDR'
        ]];

        Setting::insert($users);
    }
}
