<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use App\Services\CurrencyService;

class Currency extends Model
{
    //
    protected $table = 'currencies';

    protected $guarded = [];

    public static function convertCurrency($value)
    {
        $active_cur = CurrencyService::getCurrentCurrency();

        return round( $value/$active_cur->course_to_uah, 2);
    }

    public static function getPrice($value)
    {
        $value = self::convertCurrency($value);
        $value = round(self::recalculationIncludingTax($value), 2);

        return $value;
    }

    public static function recalculationIncludingTax($value)
    {
        $tax = auth()->user()->price_tax_status;
        if ($tax == "with_tax") {
            $value = $value * 1.2;
        }

        return $value;
    }
}
