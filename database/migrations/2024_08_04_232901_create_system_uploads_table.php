<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 50)->unique();
            $table->string('original_file', 512);
            $table->string('mime_type', 50);
            $table->string('server_file', 512)->unique();
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
        Schema::dropIfExists('system_uploads');
    }
}
