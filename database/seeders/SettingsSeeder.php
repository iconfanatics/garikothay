<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SettingType;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate settings first to ensure a clean rebrand
        Setting::truncate();

        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Garikothay', 'type' => SettingType::Text],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Find trusted cars, bikes, and auto deals across Bangladesh.', 'type' => SettingType::Text],
            ['group' => 'general', 'key' => 'site_logo', 'value' => 'settings/garikothay_logo.png', 'type' => SettingType::Image],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => null, 'type' => SettingType::Image],
            ['group' => 'general', 'key' => 'free_shipping_threshold', 'value' => '1500', 'type' => SettingType::Number],
            ['group' => 'logistics', 'key' => 'dhaka_city_shipping_charge', 'value' => '80', 'type' => SettingType::Number],
            ['group' => 'logistics', 'key' => 'outside_dhaka_shipping_charge', 'value' => '150', 'type' => SettingType::Number],
            ['group' => 'logistics', 'key' => 'delivery_time', 'value' => '2-5 business days', 'type' => SettingType::Text],
            ['group' => 'logistics', 'key' => 'delivery_partner', 'value' => 'Steadfast', 'type' => SettingType::Text],
            // Checkout
            ['group' => 'checkout', 'key' => 'guest_checkout_enabled', 'value' => '1', 'type' => SettingType::Boolean],
            // Contact
            ['group' => 'contact', 'key' => 'phone', 'value' => '+880 1911-223344', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'email', 'value' => 'support@garikothay.com', 'type' => SettingType::Text],
            ['group' => 'contact', 'key' => 'address', 'value' => 'House 24, Road 7, Banani, Dhaka 1213, Bangladesh', 'type' => SettingType::Textarea],
            ['group' => 'contact', 'key' => 'whatsapp', 'value' => '8801911223344', 'type' => SettingType::Text],
            // Social
            ['group' => 'social', 'key' => 'facebook', 'value' => 'https://facebook.com/garikothay', 'type' => SettingType::Text],
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/garikothay', 'type' => SettingType::Text],
            ['group' => 'social', 'key' => 'youtube', 'value' => 'https://youtube.com/@garikothay', 'type' => SettingType::Text],
            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'Garikothay - Trusted Cars, Bikes & Auto Deals in Bangladesh', 'type' => SettingType::Text],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Browse verified cars, bikes, spare parts, and auto deals from trusted sellers across Bangladesh with Garikothay.', 'type' => SettingType::Textarea],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'value' => '', 'type' => SettingType::Text],
            ['group' => 'seo', 'key' => 'facebook_pixel_id', 'value' => '', 'type' => SettingType::Text],
            // Payment
            ['group' => 'payment', 'key' => 'cod_enabled', 'value' => '1', 'type' => SettingType::Boolean],
            ['group' => 'payment', 'key' => 'sslcommerz_enabled', 'value' => '1', 'type' => SettingType::Boolean],
            ['group' => 'payment', 'key' => 'stripe_enabled', 'value' => '0', 'type' => SettingType::Boolean],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
