<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income
            ['name' => 'Gaji', 'icon' => '💼', 'color' => '#10b981', 'type' => 'income'],
            ['name' => 'Freelance', 'icon' => '💻', 'color' => '#3b82f6', 'type' => 'income'],
            ['name' => 'Investasi', 'icon' => '📈', 'color' => '#8b5cf6', 'type' => 'income'],
            
            // Expense
            ['name' => 'Makanan', 'icon' => '🍔', 'color' => '#f97316', 'type' => 'expense'],
            ['name' => 'Transportasi', 'icon' => '🚗', 'color' => '#3b82f6', 'type' => 'expense'],
            ['name' => 'Hiburan', 'icon' => '🎮', 'color' => '#ec4899', 'type' => 'expense'],
            ['name' => 'Belanja', 'icon' => '🛒', 'color' => '#a855f7', 'type' => 'expense'],
            ['name' => 'Tagihan', 'icon' => '📱', 'color' => '#ef4444', 'type' => 'expense'],
            ['name' => 'Kesehatan', 'icon' => '🏥', 'color' => '#14b8a6', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}