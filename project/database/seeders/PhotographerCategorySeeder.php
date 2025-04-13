<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhotographerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vérifier si la catégorie existe déjà
        $exists = DB::table('service_categories')
            ->where('name', 'photographer')
            ->exists();

        if (!$exists) {
            DB::table('service_categories')->insert([
                'name' => 'photographer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
