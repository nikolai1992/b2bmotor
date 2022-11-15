<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.07.2022
 * Time: 23:45
 */

namespace App\Services;

use App\Currency;
use Session;

class CurrencyService
{
    public static function getCurrentCurrency()
    {
        $currency_id = Session::get('current_currency');
        if ($currency_id) {
            $active_cur = Currency::find($currency_id);
        } else {
            $active_cur = Currency::first();
        }

        return $active_cur;
    }
}