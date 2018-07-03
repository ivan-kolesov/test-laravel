<?php declare(strict_types = 1);

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('feeds')->insert([

            ['url' => 'https://www.engadget.com/rss.xml', 'name' => 'engadget.com'],
            ['url' => 'https://www.cnet.com/rss/news/', 'name' => 'cnet.com/news'],

        ]);
    }
}