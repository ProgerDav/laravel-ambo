<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Link::factory(30)->create()->each(function (Link $link) {
            $link->products()->attach(Product::inRandomOrder()->first()->id);
        });
    }
}
