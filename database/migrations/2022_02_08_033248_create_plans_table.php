<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('currency', 12);
            $table->tinyInteger('decimals')->nullable();
            $table->float('amount_month')->nullable();
            $table->float('amount_year')->nullable();
            $table->text('coupons')->nullable();
            $table->text('tax_rates')->nullable();
            $table->tinyInteger('visibility')->nullable();
            $table->text('features')->nullable();
            $table->string('color', 32)->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        DB::table('plans')->insert([
            'name' => 'Default',
            'description' => 'This is the default plan.',
            'currency' => '',
            'decimals' => NULL,
            'amount_month' => 0,
            'amount_year' => 0,
            'features' => '{"text_chat":"1","file_share":"1","screen_share":"1","whiteboard":"1","hand_raise":"1","meeting_no":"50","time_limit":"60","recording":"1"}'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
