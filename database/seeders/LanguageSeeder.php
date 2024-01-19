<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'code' => 'en',
            'name' => 'English',
            'direction' => 'ltr',
            'default' => 'yes',
            'status' => 'active'
        ]);

        Language::create([
            'code' => 'es',
            'name' => 'Spanish',
            'direction' => 'ltr',
            'default' => 'no',
            'status' => 'active'
        ]);
    }
}
