<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BannerType;
use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate banners to prevent leftovers
        Banner::truncate();

        $banners = [
            [
                'type' => BannerType::HeroSlider,
                'sort_order' => 1,
                'link' => '/shop',
                'is_active' => true,
                'en' => ['title' => 'Upgrade Your Gear', 'subtitle' => 'Get up to 20% Off on Premium Mechanical Keyboards & Gaming Mice'],
                'bn' => ['title' => 'আপনার গিয়ার আপগ্রেড করুন', 'subtitle' => 'প্রিমিয়াম মেকানিক্যাল কিবোর্ড এবং গেমিং মাউসে ২০% পর্যন্ত ছাড়'],
            ],
            [
                'type' => BannerType::HeroSlider,
                'sort_order' => 2,
                'link' => '/shop',
                'is_active' => true,
                'en' => ['title' => 'Speed Up Your System', 'subtitle' => 'Unleash extreme performance with high-speed SSDs & RAM modules'],
                'bn' => ['title' => 'আপনার পিসি ফাস্ট করুন', 'subtitle' => 'হাই-স্পিড এসএসডি এবং র‍্যামের সাথে চমৎকার পারফরম্যান্স'],
            ],
            [
                'type' => BannerType::Promotional,
                'sort_order' => 1,
                'link' => '/shop',
                'is_active' => true,
                'en' => ['title' => 'Free Delivery Above ৳1500', 'subtitle' => 'Shop now and save on shipping costs'],
                'bn' => ['title' => '৳১৫০০-এর উপরে ফ্রি ডেলিভারি', 'subtitle' => 'এখনই কেনাকাটা করুন এবং শিপিং চার্জ বাঁচান'],
            ],
        ];

        foreach ($banners as $index => $data) {
            $imagePath = 'banners/placeholder.jpg';
            if ($index === 0) {
                $imagePath = 'products/keyboard.png'; // Reusing beautiful generated images for the banners
            } elseif ($index === 1) {
                $imagePath = 'products/ssd.png';
            }

            $banner = Banner::create([
                'type' => $data['type'],
                'sort_order' => $data['sort_order'],
                'link' => $data['link'],
                'is_active' => $data['is_active'],
                'image' => $imagePath,
            ]);
            $banner->setTranslation('en', $data['en']);
            $banner->setTranslation('bn', $data['bn']);
        }
    }
}
