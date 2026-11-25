<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            [
                'name' => 'Black',
                'hex_code' => '#000000',
            ],
            [
                'name' => 'White',
                'hex_code' => '#FFFFFF',
            ],
            [
                'name' => 'Red',
                'hex_code' => '#FF0000',
            ],
            [
                'name' => 'Blue',
                'hex_code' => '#0000FF',
            ],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}