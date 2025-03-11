<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $randArray = [null, 1,2,3,4,5,6,7,8,9,10];

    //     Category::factory(50)->create()->each(function ($categories) use ($randArray){
    //         $category->parent_id = $randArray[rand(0, count($randArray) - 1)];
    //         $category->save();
    //     });
    // }
    
    public function run(): void
    {
        $mainCategories = Category::factory(10)->create();

        Category::factory(40)->create([
            'parent_id' => fn () => $mainCategories->random()->id,
        ]);
    }

}


    
