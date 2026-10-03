<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CriteriaSeeder::class,
            UserAndVendorSeeder::class,
            BundlingSeeder::class,
            BundlingEvaluationSeeder::class,
            GallerySeeder::class,
            OrderReviewSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
