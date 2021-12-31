<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAllFieldsInIdSocialNetworksNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('id_social_networks', function (Blueprint $table) {
            $table->unsignedBigInteger('yandex_id')->nullable()->change();
            $table->unsignedBigInteger('google_id')->nullable()->change();
            $table->unsignedBigInteger('vkontakte_id')->nullable()->change();
            $table->unsignedBigInteger('odnoklassniki_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('id_social_networks_nullable', function (Blueprint $table) {
            $table->unsignedBigInteger('yandex_id')->change();
            $table->unsignedBigInteger('google_id')->change();
            $table->unsignedBigInteger('vkontakte_id')->change();
            $table->unsignedBigInteger('odnoklassniki_id')->change();
        });
    }
}
