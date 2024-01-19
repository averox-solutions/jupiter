<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class V16 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('global_config')->insert(
            [
                [
                    'key' => 'GOOGLE_RECAPTCHA',
                    'value' => 'disabled'
                ],
                [
                    'key' => 'GOOGLE_RECAPTCHA_KEY',
                    'value' => ''
                ],
                [
                    'key' => 'GOOGLE_RECAPTCHA_SECRET',
                    'value' => ''
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
