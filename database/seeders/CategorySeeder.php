<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Saúde', 'icon' => '🍎', 'color' => 'indigo'],
            ['name' => 'Produtividade', 'icon' => '💻', 'color' => 'indigo'],
            ['name' => 'Estudos', 'icon' => '📚', 'color' => 'indigo'],
            ['name' => 'Financeiro', 'icon' => '💰', 'color' => 'indigo'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert(array_merge($cat, [
                'created_at' => now(), 
                'updated_at' => now()
            ]));
        }
    }
}
