<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'menu',
            'vetements',
            'negafa',
            'maquillage',
            'salles',
            'decoration',
            'amariya',
            'animation'
        ];

        // Insertion des catégories
        foreach ($categories as $category) {
            DB::table('service_categories')->insert([
                'name' => $category,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
