<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //获取所有用户
        $users = User::all();

        //获取 1 号用户
        $user = $users->first();
        $user_id = $user->id;

        //获取除了 1 号用户外的所有用户
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        //关注除了 1 号用户的所有用户
        $user->follow($follower_ids);

        //其余用户关注 1 号用户
        foreach ($followers as $follower) {
        	$follower->follow($user_id);
        }

    }
}
