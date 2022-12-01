<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('status', ['free', 'reserved']);
            $table->unsignedBigInteger('current_reserver_id')->nullable();
            $table->unsignedBigInteger('publisher_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            $table->foreign('current_reserver_id')->references('id')->on('users');
            $table->foreign('publisher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
