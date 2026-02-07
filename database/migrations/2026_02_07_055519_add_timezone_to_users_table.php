<?php
// database/migrations/2024_01_01_000008_add_timezone_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimezoneToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone', 50)
                  ->default('UTC')
                  ->after('role');
            
            $table->string('country_code', 2)
                  ->nullable()
                  ->after('timezone');
            
            $table->string('locale', 10)
                  ->default('en')
                  ->after('country_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'country_code', 'locale']);
        });
    }
}