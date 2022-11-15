<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard.index');
    }

    public function refreshTaxSellector()
    {
        $price_tax_status = auth()->user()->price_tax_status;

        return $price_tax_status ? $price_tax_status : 'without_tax';
    }
}
