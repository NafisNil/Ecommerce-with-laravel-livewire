<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Setting;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $setitngs = [
            ['key' => 'store_name', 'value' => 'My E-commerce Store', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_email', 'value' => 'info@example.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_phone', 'value' => '+1 234 567 890', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_address', 'value' => '123 Main St, City, Country', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_currency', 'value' => 'USD', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_language', 'value' => 'en', 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_logo', 'value' => null, 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_favicon', 'value' => null, 'type' => 'string', 'group' => 'general'],
            ['key' => 'store_meta_title', 'value' => null, 'type' => 'string', 'group' => 'seo'],
            ['key' => 'store_meta_description', 'value' => null, 'type' => 'string', 'group' => 'seo'],
            ['key' => 'store_meta_keywords', 'value' => null, 'type' => 'string', 'group' => 'seo'],
            ['key' => 'store_analytics_code', 'value' => null, 'type' => 'string', 'group' => 'analytics'],
            ['key' => 'store_facebook_pixel', 'value' => null, 'type' => 'string', 'group' => 'analytics'],
            ['key' => 'store_google_ads', 'value' => null, 'type' => 'string', 'group' => 'analytics'],

        ];

        foreach ($setitngs as $setting) {
            Setting::create($setting);
        }
    }
}
