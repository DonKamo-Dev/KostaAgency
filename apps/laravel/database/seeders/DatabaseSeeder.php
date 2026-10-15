<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InitialSetupSeeder::class,
            CaseStudiesSeeder::class,
        ]);

        $exportFile = database_path('seeders/data/render_export.json');
        if (file_exists($exportFile)) {
            \Illuminate\Support\Facades\Artisan::call('kosta:import-data', ['file' => $exportFile]);
        }
    }
}
