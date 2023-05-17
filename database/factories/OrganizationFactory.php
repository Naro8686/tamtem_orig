<?php

use App\Models\Org\Organization;
use App\Models\User;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Organization::class, function (Faker $faker) {
    return [
        'org_name' => $faker->sentence,
        'org_city_id' => 108,
        'org_inn' => $faker->numberBetween(100000000000, 999999999999),
        'org_legal_form_id' => 1,
        'org_type' => Organization::ORG_TYPE_BUYER,
        'org_status' => Organization::ORG_STATUS_APPROVE,
        'on_moderate' => 1,
        'org_manager_post'  => $faker->sentence,
        'org_okved'         => $faker->sentence,
        'org_ogrn'          => $faker->sentence,
        'org_is_active'     => true,
        'org_registration_date' => now(),
    ];
});

$factory->state(Organization::class, 'user', function () {
    /** @var User $user */
    $user = factory(User::class)->create();

    return ['owner_id' => $user->id];
});