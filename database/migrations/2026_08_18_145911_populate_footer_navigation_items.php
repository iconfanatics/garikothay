<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\NavigationItem;
use App\Models\NavigationItemTranslation;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove old footer links
        $oldItems = NavigationItem::whereIn('group', ['footer_quick_links', 'footer_customer_service', 'footer_company', 'footer_business', 'footer_help', 'footer_legal'])->get();
        foreach ($oldItems as $item) {
            $item->delete();
        }

        $links = [
            'footer_company' => [
                ['url' => '/page/about-us', 'en' => 'About Us', 'bn' => 'আমাদের সম্পর্কে'],
                ['url' => '/page/our-team', 'en' => 'Our Team', 'bn' => 'আমাদের টিম'],
                ['url' => '/page/careers', 'en' => 'Careers', 'bn' => 'ক্যারিয়ার'],
                ['url' => '/contact', 'en' => 'Contact Us', 'bn' => 'যোগাযোগ করুন'],
                ['url' => '/page/press-and-media', 'en' => 'Press & Media', 'bn' => 'প্রেস ও মিডিয়া'],
                ['url' => '/page/partners', 'en' => 'Partners', 'bn' => 'পার্টনার্স'],
            ],
            'footer_business' => [
                ['url' => '/page/why-list-with-us', 'en' => 'Why List With Us', 'bn' => 'কেন আমাদের সাথে যুক্ত হবেন'],
                ['url' => '/page/advertise-with-us', 'en' => 'Advertise With Us', 'bn' => 'বিজ্ঞাপন দিন'],
                ['url' => '/page/trust-and-verification', 'en' => 'Trust & Verification', 'bn' => 'ট্রাস্ট ও ভেরিফিকেশন'],
                ['url' => '/page/business-listing-plans', 'en' => 'Business Listing Plans', 'bn' => 'লিস্টিং প্ল্যান'],
                ['url' => '/page/partner-with-us', 'en' => 'Partner With Us', 'bn' => 'আমাদের সাথে কাজ করুন'],
            ],
            'footer_help' => [
                ['url' => '/page/feedback', 'en' => 'Feedback', 'bn' => 'মতামত'],
                ['url' => '/page/tips-and-guide', 'en' => 'Tips & Guide', 'bn' => 'টিপস এবং গাইড'],
                ['url' => '/page/faq', 'en' => 'FAQ', 'bn' => 'সাধারণ জিজ্ঞাসা'],
                ['url' => '/page/help-center', 'en' => 'Help Center', 'bn' => 'হেল্প সেন্টার'],
                ['url' => '/page/report-a-problem', 'en' => 'Report A Problem', 'bn' => 'সমস্যা রিপোর্ট করুন'],
                ['url' => '/page/safe-shopping-guidelines', 'en' => 'Safe Shopping Guidelines', 'bn' => 'নিরাপদ শপিং গাইড'],
            ],
            'footer_legal' => [
                ['url' => '/page/privacy-policy', 'en' => 'Privacy Policy', 'bn' => 'প্রাইভেসি পলিসি'],
                ['url' => '/page/terms-and-conditions', 'en' => 'Terms & Condition', 'bn' => 'শর্তাবলী'],
                ['url' => '/page/delivery-policy', 'en' => 'Delivery Policy', 'bn' => 'ডেলিভারি পলিসি'],
                ['url' => '/page/refund-and-return-policy', 'en' => 'Refund & Return Policy', 'bn' => 'রিফান্ড ও রিটার্ন পলিসি'],
                ['url' => '/page/exchange-policy', 'en' => 'Exchange Policy', 'bn' => 'এক্সচেঞ্জ পলিসি'],
                ['url' => '/page/cancellation-policy', 'en' => 'Cancellation Policy', 'bn' => 'বাতিল পলিসি'],
                ['url' => '/page/warranty-policy', 'en' => 'Warranty Policy', 'bn' => 'ওয়ারেন্টি পলিসি'],
                ['url' => '/page/emi-and-payment-policy', 'en' => 'EMI & Payment Policy', 'bn' => 'ইএমআই ও পেমেন্ট'],
            ],
        ];

        foreach ($links as $group => $items) {
            foreach ($items as $index => $itemData) {
                $item = NavigationItem::create([
                    'group' => $group,
                    'url' => $itemData['url'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);

                $item->translations()->createMany([
                    ['locale' => 'en', 'label' => $itemData['en']],
                    ['locale' => 'bn', 'label' => $itemData['bn']],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
