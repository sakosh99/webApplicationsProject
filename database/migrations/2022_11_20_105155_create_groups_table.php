<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->enum('group_type', ['public', 'private', 'shared']);
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->timestamps();
            $table->foreign('publisher_id')->references('id')->on('users')->onDelete('cascade');
        });
        DB::table('groups')->insert([
            [
                'id' => 1,
                'group_name' => 'public',
                'group_type' => 'public',
                'publisher_id' => null,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
