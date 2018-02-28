<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {	

    	//所有用户的 ID 数组
    	$user_ids = User::all()->pluck('id')->toArray();

    	//所有话题的 ID 数组
    	$topic_ids = Topic::all()->pluck('id')->toArray();

    	//获取 Faker 示例
    	$faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
        				->times(1000)
        				->make()
        				->each(function ($reply, $index) use($user_ids, $topic_ids, $faker) 
        {
            //从用户 ID 数组中随机获取值插入
            $reply->user_id = $faker->randomElement($user_ids);
            //从话题 ID 数组中。。。。
            $reply->topic_id = $faker->randomElement($topic_ids);
        });

        //插入数据库
        Reply::insert($replys->toArray());
    }

}

