<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBudgetFromAndBudgetToOrganizationsDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_from DECIMAL(14,2) NOT NULL DEFAULT '0.00';");
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_to DECIMAL(14,2) NOT NULL DEFAULT '0.00';");
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_old DECIMAL(14,2) NOT NULL DEFAULT '0.00';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_from INT NOT NULL DEFAULT 0;");
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_to INT NOT NULL DEFAULT 0;");
        DB::statement("ALTER TABLE organizations_deals MODIFY COLUMN budget_old INT NOT NULL DEFAULT 0;");
    }
}
