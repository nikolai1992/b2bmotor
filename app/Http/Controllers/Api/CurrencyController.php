<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    //
    public function update(Request $request, $id)
    {
        $currency = Currency::where('uuid', '=', $id)->firstOrFail();
        $currency->course_to_uah = (string)$request->currencies;
        $currency->save();

        return response()->json($currency, 200);
    }
}
