<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        News::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 5; $i++)
        {
            News::create([
                'title' => $faker->word,
                'description' => $faker->text,
                'image_url' => 'images/gambar1.jpg',
                'user_id' => $faker->randomDigit,
                
            ]);
        }
    }
}
