<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('user_name')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user']);
            $table->timestamps();
        });
        DB::table('users')->insert([
            [
                'id' => 1,
                'full_name' => 'admin_name',
                'email' => 'admin@admin.com',
                'user_name' => 'admin',
                'password' => Hash::make('123456'),
                'role' => 'Admin'
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
        Schema::dropIfExists('users');
    }
}
