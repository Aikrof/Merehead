<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('jwtUid')->unique()->nullable(true)->default(null);
            $table->float('dateCreated', 20)->nullable(false);
            $table->float('dateUpdated', 20)->nullable(false);

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
