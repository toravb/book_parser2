<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempTokenColumnToIdSocialNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('id_social_networks', function (Blueprint $table) {
            $table->string('temp_token')->nullable()->unique()->after('odnoklassniki_id');
            $table->timestamp('token_valid_until')->nullable()->after('temp_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('id_social_networks', function (Blueprint $table) {
            $table->dropColumn('temp_token');
            $table->dropColumn('token_valid_until');
        });
    }
}
