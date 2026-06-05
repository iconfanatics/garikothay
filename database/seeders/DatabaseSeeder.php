<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            $this->call([
                AdminSeeder::class,
                SettingsSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class,
                CouponSeeder::class,
                BlogSeeder::class,
                BannerSeeder::class,
                SiteDataSeeder::class,
            ]);
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
