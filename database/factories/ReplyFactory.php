<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reply::class, function (Faker $faker) {
	//随机获取一个月以内的时间
	$date_time = $faker->dateTimeThisMonth();

    return [
        'content' => $faker->text,
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
