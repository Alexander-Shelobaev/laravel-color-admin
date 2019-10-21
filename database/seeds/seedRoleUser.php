<?php

use Illuminate\Database\Seeder;
// php artisan db:seed --class=seedRoleUser
class seedRoleUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([

			['user_id'=>'1','role_id'=>'1'],

			['user_id'=>'2','role_id'=>'2'],
			['user_id'=>'3','role_id'=>'2'],

			['user_id'=>'4','role_id'=>'3'],
			['user_id'=>'5','role_id'=>'3'],
            ['user_id'=>'6','role_id'=>'2'],
			['user_id'=>'6','role_id'=>'3'],

		]);  
    }
}
?>