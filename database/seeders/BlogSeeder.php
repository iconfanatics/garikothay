<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate tables first to ensure a clean rebrand
        Blog::truncate();
        BlogCategory::truncate();

        $adminId = Admin::first()?->id ?? 1;

        $categories = [
            ['en' => 'Buying Guides', 'bn' => 'ক্রয় গাইড'],
            ['en' => 'Tech Tips', 'bn' => 'টেক টিপস'],
            ['en' => 'Desk Setups', 'bn' => 'ডেস্ক সেটআপ'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $slug = Str::slug($cat['en']);
            $blogCat = BlogCategory::firstOrCreate(['slug' => $slug]);
            $blogCat->setTranslation('en', ['name' => $cat['en']]);
            $blogCat->setTranslation('bn', ['name' => $cat['bn']]);
            $createdCategories[] = $blogCat->fresh();
        }

        $posts = [
            [
                'en' => [
                    'title' => 'How to Choose the Right Mechanical Keyboard Switch',
                    'excerpt' => 'Confused between Red, Blue, and Brown mechanical switches? This guide explains the differences to help you select.',
                    'content' => '<p>Mechanical keyboards are highly customizable, and your typing experience largely depends on the switches you choose.</p><h2>Switch Types</h2><p>1. Linear Switches (usually Red) are quiet and smooth, excellent for fast gaming.</p><p>2. Tactile Switches (usually Brown) offer a quiet bump feedback, great for typing.</p><p>3. Clicky Switches (usually Blue) make a loud clicky sound, preferred by retro typists.</p>',
                ],
                'bn' => [
                    'title' => 'কীভাবে সঠিক মেকানিক্যাল কিবোর্ড সুইচ নির্বাচন করবেন',
                    'excerpt' => 'লাল, নীল এবং বাদামী মেকানিক্যাল সুইচের মধ্যে বিভ্রান্ত? এই গাইডটি আপনাকে সঠিকটি বেছে নিতে সাহায্য করবে।',
                    'content' => '<p>মেকানিক্যাল কিবোর্ড টাইপিংয়ের জন্য চমৎকার এবং এর অভিজ্ঞতা সুইচের ওপর নির্ভর করে।</p>',
                ],
                'category_index' => 0,
            ],
            [
                'en' => [
                    'title' => 'SSD vs HDD: Why Your PC Needs an SSD Upgrade',
                    'excerpt' => 'Is your computer running slow? Upgrading to an SSD is the single best hardware investment you can make.',
                    'content' => '<p>Solid State Drives (SSDs) have revolutionized computer storage, replacing the older spinning platter Hard Disk Drives (HDDs).</p><h2>Major Benefits of SSD</h2><p>SSDs are up to 10 times faster than standard HDDs, ensuring fast Windows boot times and instant app loading.</p>',
                ],
                'bn' => [
                    'title' => 'এসএসডি বনাম হার্ডডিস্ক: আপনার পিসির কেন একটি এসএসডি প্রয়োজন',
                    'excerpt' => 'আপনার কম্পিউটার কি ধীরগতির হয়ে গিয়েছে? একটি এসএসডি আপগ্রেড আপনাকে দিতে পারে সুপারফাস্ট গতি।',
                    'content' => '<p>সলিড স্টেট ড্রাইভ (এসএসডি) আপনার কম্পিউটারের গতি বহুগুণ বাড়িয়ে দেয়।</p>',
                ],
                'category_index' => 1,
            ],
            [
                'en' => [
                    'title' => 'Ultimate Guide to Cable Management for Clean Desks',
                    'excerpt' => 'Organize messy cables on your workstation with our step-by-step setup guides and tools.',
                    'content' => '<p>A clean desk leads to a productive mind. Cable management is key to maintaining a premium aesthetic setup.</p><h2>Tips to Manage Cables</h2><p>Use sleeve organizers, cable clips, and under-desk trays to hide all power lines.</p>',
                ],
                'bn' => [
                    'title' => 'ক্লিন ডেস্কের জন্য ক্যাবল ম্যানেজমেন্ট গাইড',
                    'excerpt' => 'আমাদের ধাপে ধাপে নির্দেশনার সাহায্যে আপনার ওয়ার্কস্টেশনের অগোছালো তারগুলো সাজিয়ে নিন।',
                    'content' => '<p>একটি গোছানো ডেস্ক আপনার কাজের উৎপাদনশীলতা বাড়ায়। ক্যাবল ম্যানেজমেন্টের মাধ্যমে ডেস্ক রাখুন ছবির মতো পরিষ্কার।</p>',
                ],
                'category_index' => 2,
            ],
        ];

        foreach ($posts as $post) {
            $slug = Str::slug($post['en']['title']);
            $blog = Blog::firstOrCreate(['slug' => $slug], [
                'blog_category_id' => $createdCategories[$post['category_index']]->id,
                'author_id' => $adminId,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
            $blog->setTranslation('en', $post['en']);
            $blog->setTranslation('bn', $post['bn']);
        }
    }
}
