<?php
// database/seeders/CategorySeeder.php

//namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $admin = User::first();
        }

        $categories = [
            [
                'name' => 'Financial Documents',
                'description' => 'All financial related documents',
                'parent_id' => null,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'HR Documents',
                'description' => 'Human resources documents',
                'parent_id' => null,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Legal Documents',
                'description' => 'Legal contracts and agreements',
                'parent_id' => null,
                'created_by' => $admin->id,
            ],
            [
                'name' => 'Technical Documents',
                'description' => 'Technical specifications and documentation',
                'parent_id' => null,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create subcategories
        $financial = Category::where('name', 'Financial Documents')->first();
        
        if ($financial) {
            Category::create([
                'name' => 'Invoices',
                'description' => 'All invoices',
                'parent_id' => $financial->id,
                'created_by' => $admin->id,
            ]);

            Category::create([
                'name' => 'Receipts',
                'description' => 'All receipts',
                'parent_id' => $financial->id,
                'created_by' => $admin->id,
            ]);
        }
    }
}