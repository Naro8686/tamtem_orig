<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(KladrSeeder::class);
        $this->call(OrganizationLegalFormSeeder::class);
        $this->call(AdSErviceSeeder::class);
    }
}
