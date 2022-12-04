<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions_plans', function (Blueprint $table) {
            $table->id();
            $table->enum('plan', ['bronze', 'silver', 'gold', 'unlimited']);
            $table->integer('max_memory_size_in_mega');
            $table->double('price');
            $table->timestamps();
        });
        DB::table('subscriptions_plans')->insert([
            [
                'id' => 1,
                'plan' => 'bronze',
                'max_memory_size_in_mega' => 512,
                'price' => 10.0
            ],
            [
                'id' => 2,
                'plan' => 'silver',
                'max_memory_size_in_mega' => 2048,
                'price' => 10.0
            ],
            [
                'id' => 3,
                'plan' => 'gold',
                'max_memory_size_in_mega' => 10240,
                'price' => 10.0
            ],
            [
                'id' => 4,
                'plan' => 'unlimited',
                'max_memory_size_in_mega' => -1,
                'price' => 10.0
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
        Schema::dropIfExists('subscriptions_plans');
    }
}
