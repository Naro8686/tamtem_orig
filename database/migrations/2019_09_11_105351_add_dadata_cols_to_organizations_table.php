<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDadataColsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('org_manager_post')->after('org_director')->nullable()->comment('должность руководителя');
            $table->string('org_okved')->after('org_kpp')->nullable()->comment('ОКВЭД');
            $table->string('org_ogrn')->after('org_okved')->nullable()->comment('ОГРН');
            $table->boolean('org_is_active')->after('org_status')->default(false)->comment('статус организации Активна- Не актвина, пока по dadata');
            $table->timestamp('org_registration_date')->after('deleted_at')->nullable()->comment('дата регитрации');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            Schema::dropIfExists('org_manager_post');
            Schema::dropIfExists('org_okved');
            Schema::dropIfExists('org_ogrn');
            Schema::dropIfExists('org_is_active');
            Schema::dropIfExists('org_registration_date');
        });
    }
}
