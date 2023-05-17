<?php


use App\Models\User;
use App\Models\Org\Organization;
use App\Http\Controllers\Admin\UsersController;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
/** @var Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'unique_id' => UsersController::generateUniqueUserIdNumber(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'role' => User::ROLE_CLIENT,
        'status' => User::USER_STATUS_APPROVE,
        'phone' => "9" . $faker->numberBetween(100000000, 999999999),
    ];
});

$factory->state(User::class, 'approved', function () {
    return ['status' => User::USER_STATUS_APPROVE];
});

// $factory->state(User::class, 'organization', function (User $user) {
//     /** @var Organization $organization */
//     $organization = factory(Organization::class)->create(['owner_id' => $user->id]);
//   //  $user->organization_id = $organization->id;

//     return ['organization_id' => $organization->id];
// });