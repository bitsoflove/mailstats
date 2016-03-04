<?php

use Illuminate\Database\Seeder;

class DefaultCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \BitsOfLove\MailStats\Entities\Category::create([
            'name' => config('mailstats.category.default', 'mail'),
        ]);
    }
}
