<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::brands() as $brand) {
            \App\Models\Brand::create([
                'title' => $brand['title'],
            ]);
        }
    }

    public static function brands()
    {
        return [
            ['title' => 'Alleva Equilibrium'],
            ['title' => 'Alleva Holistic'],
            ['title' => 'Alleva Natural'],
            ['title' => 'Animonda'],
            ['title' => 'Aspect'],
        ];
    }
}
