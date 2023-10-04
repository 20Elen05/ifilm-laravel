<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(){
        DB::table('categories')->insert([
            ['category_name' => 'popular'],
            ['category_name' => 'top_rated'],
            ['category_name' => 'now_playing'],
        ]);
    }
}
