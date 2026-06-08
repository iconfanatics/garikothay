<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteDataSeeder extends Seeder
{
    public function run(): void
    {
        // Add Site Logo setting if it doesn't exist
        Setting::firstOrCreate(
            ['key' => 'site_logo'],
            [
                'group' => 'general',
                'value' => 'settings/garikothay_logo.png',
                'type' => 'image',
            ]
        );

        // Truncate pages to start fresh
        Page::truncate();

        $pages = [
            [
                'slug' => 'faq',
                'en' => [
                    'title' => 'Warranty & FAQ',
                    'content' => '<h2>General Questions</h2><p>Here you can find answers to delivery times, official brand warranties, and customer support channels.</p>',
                ],
                'bn' => [
                    'title' => 'ওয়ারেন্টি ও সাধারণ জিজ্ঞাসা',
                    'content' => '<h2>সাধারণ প্রশ্নসমূহ</h2><p>এখানে আপনি ডেলিভারি সময়সীমা, অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি এবং আমাদের সাপোর্ট চ্যানেল সম্পর্কে জানতে পারবেন।</p>',
                ]
            ],
            [
                'slug' => 'return-policy',
                'en' => [
                    'title' => 'Warranty & Replacement Policy',
                    'content' => '<h2>Warranty & Refund Policy</h2><p>We provide a 7-day hassle-free replacement warranty for manufacturing defects and official brand warranty coverage.</p>',
                ],
                'bn' => [
                    'title' => 'ওয়ারেন্টি ও রিপ্লেসমেন্ট পলিসি',
                    'content' => '<h2>ওয়ারেন্টি এবং রিফান্ড পলিসি</h2><p>আমরা উৎপাদনগত ত্রুটির জন্য ৭ দিনের সহজ রিপ্লেসমেন্ট ওয়ারেন্টি এবং অফিসিয়াল ব্র্যান্ড ওয়ারেন্টি কভারেজ প্রদান করি।</p>',
                ]
            ],
            [
                'slug' => 'about',
                'en' => [
                    'title' => 'About Us',
                    'content' => '<h2>Welcome to Garikothay</h2><p>Your trusted place to discover cars, bikes, parts, and auto services from reliable sellers across Bangladesh.</p>',
                ],
                'bn' => [
                    'title' => 'আমাদের সম্পর্কে',
                    'content' => '<h2>গাড়িকোথায়-এ স্বাগতম</h2><p>বাংলাদেশজুড়ে বিশ্বস্ত বিক্রেতাদের গাড়ি, বাইক, পার্টস এবং অটো সার্ভিস খুঁজে পাওয়ার নির্ভরযোগ্য জায়গা।</p>',
                ]
            ],
            [
                'slug' => 'terms',
                'en' => [
                    'title' => 'Terms & Conditions',
                    'content' => '<h2>Terms of Service</h2><p>By using Garikothay, you agree to our listing, seller communication, and secure transaction guidelines.</p>',
                ],
                'bn' => [
                    'title' => 'শর্তাবলী',
                    'content' => '<h2>সেবার শর্তাবলী</h2><p>গাড়িকোথায় ব্যবহার করলে আমাদের লিস্টিং, বিক্রেতার সাথে যোগাযোগ এবং নিরাপদ লেনদেনের নির্দেশনা প্রযোজ্য হবে।</p>',
                ]
            ],
            [
                'slug' => 'privacy',
                'en' => [
                    'title' => 'Privacy Policy',
                    'content' => '<h2>Privacy Policy</h2><p>Your security is our absolute priority. We do not sell your personal or billing information to anyone.</p>',
                ],
                'bn' => [
                    'title' => 'গোপনীয়তা নীতি',
                    'content' => '<h2>গোপনীয়তা নীতি</h2><p>আপনার তথ্যের নিরাপত্তা আমাদের সর্বোচ্চ অগ্রাধিকার। আমরা আপনার ব্যক্তিগত বা পেমেন্ট সংক্রান্ত তথ্য অন্য কারো সাথে শেয়ার করি না।</p>',
                ]
            ]
        ];

        foreach ($pages as $data) {
            $page = Page::firstOrCreate(['slug' => $data['slug']]);

            $page->setTranslation('en', [
                'title' => $data['en']['title'],
                'content' => $data['en']['content'],
            ]);

            $page->setTranslation('bn', [
                'title' => $data['bn']['title'],
                'content' => $data['bn']['content'],
            ]);
        }
    }
}
