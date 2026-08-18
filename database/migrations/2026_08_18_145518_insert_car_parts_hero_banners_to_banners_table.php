<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use App\Models\BannerTranslation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BannerTranslation::query()->delete();
        Banner::query()->delete();

        $b1 = Banner::create([
            'type' => 'hero_slider',
            'link' => '/shop',
            'sort_order' => 1,
            'is_active' => true,
            'image' => 'theme/hero-1.jpg',
        ]);
        $b1->translations()->createMany([
            ['locale' => 'en', 'title' => 'Car Parts Mega Sale', 'subtitle' => 'Up to 50% off on genuine engine, brake & suspension parts.', 'button_text' => 'Shop Parts'],
            ['locale' => 'bn', 'title' => 'গাড়ির পার্টস মেগা সেল', 'subtitle' => 'আসল ইঞ্জিন, ব্রেক এবং সাসপেনশন পার্টস-এ ৫০% পর্যন্ত ছাড়।', 'button_text' => 'পার্টস কিনুন']
        ]);

        $b2 = Banner::create([
            'type' => 'hero_slider',
            'link' => '/shop',
            'sort_order' => 2,
            'is_active' => true,
            'image' => 'theme/hero-2.jpg',
        ]);
        $b2->translations()->createMany([
            ['locale' => 'en', 'title' => 'Premium Car Accessories', 'subtitle' => 'Upgrade your ride with our new collection of interior and exterior accessories.', 'button_text' => 'Explore Accessories'],
            ['locale' => 'bn', 'title' => 'প্রিমিয়াম কার এক্সেসরিজ', 'subtitle' => 'আমাদের নতুন ইন্টেরিয়র এবং এক্সটেরিয়র এক্সেসরিজ কালেকশন দিয়ে আপনার রাইড আপগ্রেড করুন।', 'button_text' => 'এক্সেসরিজ দেখুন']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
};
