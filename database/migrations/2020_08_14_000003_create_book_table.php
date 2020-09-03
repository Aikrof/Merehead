<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->string('uid')->unique()->primary();
            $table->string('user_uid')->nullable(false);
            $table->string('name')->nullable(false);
            $table->integer('n_pages')->nullable(false);
            $table->text('annotation')->nullable(false);
            $table->string('img')->nullable(false);
            $table->string('author_uid')->nullable(false);
            $table->float('dateCreated', 20)->nullable(false);
            $table->float('dateUpdated', 20)->nullable(false);

            $table->foreign('user_uid')->references('uid')->on('user');
            $table->foreign('author_uid')->references('uid')->on('author')->onDelete('cascade');

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
        Schema::dropIfExists('book');
    }
}
