<?php

use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channelmail = \App\Models\Org\Channel::create(['name' => 'mail']);

        $users = \App\Models\User::where('is_org_created', '=', 1)->get();
        foreach ($users as $user){
            $user->company->channels()->syncWithoutDetaching($channelmail->id);
        }
    }
}
