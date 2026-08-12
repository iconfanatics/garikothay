<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class CompliancePagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<h1>Terms and Conditions</h1><p>Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use.</p>'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>This privacy policy sets out how we use and protect any information that you give us when you use this website.</p>'
            ],
            [
                'title' => 'Return and Refund Policy',
                'slug' => 'refund-and-return-policy',
                'content' => '<h1>Return and Refund Policy</h1><p>If you are not entirely satisfied with your purchase, we\'re here to help.</p><h2>Refunds</h2><p>Once we receive your item, we will inspect it and notify you that we have received your returned item. If your return is approved, we will initiate a refund to your original method of payment. The standard timeline for a refund is <strong>7 to 10 working days</strong>.</p>'
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About Us</h1><p>Garikothay is Bangladesh\'s premier online IT & Computer Accessories store.</p><h2>Our Management</h2><p>Driven by a passion for technology, our experienced management team strives to deliver the best products and services to our customers.</p>'
            ],
            [
                'title' => 'Delivery Policy',
                'slug' => 'delivery-policy',
                'content' => '<h1>Delivery Policy</h1><p>We strive to deliver your orders as quickly as possible.</p><p><strong>Standard Delivery Time:</strong></p><ul><li>Inside Dhaka: 5 days</li><li>Outside Dhaka: 10 days</li></ul>'
            ]
        ];

        foreach ($pages as $pageData) {
            $page = Page::firstOrCreate(['slug' => $pageData['slug']]);
            
            // Only update if translations are empty or missing
            $translations = $page->translations ?? [];
            if (empty($translations) || $translations->isEmpty()) {
                $page->setTranslation('en', [
                    'title' => $pageData['title'],
                    'content' => $pageData['content']
                ]);
                $page->save();
            }
        }
    }
}
