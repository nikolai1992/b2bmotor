<?php

use Illuminate\Database\Seeder;

use App\Currency;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Currency::create([
            'name' => 'Гривна',
            'course_to_uah' => '1',
            'alias' => 'uah'
        ]);

//        Currency::create([
//            'name' => 'Доллар',
//            'course_to_uah' => '37.40',
//            'alias' => 'usd'
//        ]);

        Currency::create([
            'name' => 'Евро',
            'course_to_uah' => '37.65',
            'alias' => 'eur'
        ]);
    }
}
