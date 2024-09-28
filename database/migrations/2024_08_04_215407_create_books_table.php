<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('author')->nullable();
            $table->string('subject')->nullable();
            $table->string('isbn')->nullable();
            $table->string('description')->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->double('price_discount', 10, 2)->nullable();
            $table->string('url', 300)->nullable();
            $table->string('link')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
