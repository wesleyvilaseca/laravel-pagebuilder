<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_relations', function (Blueprint $table) {
            $table->id();
            $table->integer('system_upload_id')->nullable();
            $table->integer('relation_id')->nullable();
            $table->string('alias_model_relation')->nullable();
            $table->string('alias_category')->nullable();
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
        Schema::dropIfExists('upload_relations');
    }
}
