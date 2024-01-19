<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('billing_information')->nullable()->after('role');
            $table->timestamp('plan_ends_at')->nullable()->after('role');
            $table->timestamp('plan_trial_ends_at')->nullable()->after('role');
            $table->timestamp('plan_recurring_at')->nullable()->after('role');
            $table->timestamp('plan_created_at')->nullable()->after('role');
            $table->string('plan_subscription_status', 32)->nullable()->after('role');
            $table->string('plan_subscription_id', 128)->nullable()->after('role');
            $table->string('plan_payment_gateway', 32)->nullable()->after('role');
            $table->string('plan_interval', 16)->nullable()->after('role');
            $table->string('plan_currency', 12)->nullable()->after('role');
            $table->string('plan_amount', 32)->nullable()->after('role');
            $table->integer('plan_id')->unsigned()->default(1)->after('role')->index('plan_id');
            $table->smallInteger('default_stats')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
