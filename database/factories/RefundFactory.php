<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Refund;
use Faker\Generator as Faker;

$factory->define(Refund::class, function (Faker $faker) {
	return [
		'date' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null),
		'type' => 'TICKET',
		'description' => '...',
		'value' => 108.90,
		'approved' => false,
		'person_id' => 1
	];
});
