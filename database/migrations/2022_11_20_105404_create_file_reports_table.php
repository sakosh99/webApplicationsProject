<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['create', 'update', 'move', 'copy', 'rename', 'reserve', 'cancel_reserve']);
            $table->unsignedBigInteger('to_group')->nullable();
            $table->string('old_file_name')->nullable();
            $table->string('new_file_name')->nullable();
            $table->timestamp('created_at');
            $table->foreign('to_group')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_reports');
    }
}
