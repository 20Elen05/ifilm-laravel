<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(){
        DB::table('categories')->insert([
            ['category_name' => 'Popular'],
            ['category_name' => 'top_rated'],
            ['category_name' => 'now_playing'],
        ]);
    }
}
