<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::categories() as $brand) {
            \App\Models\Category::create([
                'title' => $brand['title'],
            ]);
        }
    }

    public static function categories()
    {
        return [
            ['title' => 'Dry dog food'],
            ['title' => 'Pet treats and chews'],
            ['title' => 'Flea and tick medication'],
            ['title' => 'Pet grooming supplies'],
            ['title' => 'Pet vitamins and supplements'],
            ['title' => 'Heartworn medication'],
            ['title' => 'Pet oral care'],
            ['title' => 'Wet pet food'],
            ['title' => 'Cat litter'],
            ['title' => 'Pet clean-up and outdoor'],
        ];
    }
}
