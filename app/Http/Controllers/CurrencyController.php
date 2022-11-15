<?php

namespace App\Http\Controllers;

use Session;

class CurrencyController extends Controller
{
    //
    public function changeCurrency($id)
    {
        Session::put('current_currency', $id);
    }
}
