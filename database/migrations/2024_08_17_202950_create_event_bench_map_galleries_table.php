<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventBenchMapGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_bench_map_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->nullable();
            $table->string('name')->nullable();
            $table->string('description', 500)->nullable();
            $table->integer('order')->nullable();
            $table->string('link')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('event_bench_map_galleries');
    }
}
