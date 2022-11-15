<?php

namespace App\Console\Commands;

use App\Product;
use App\User;
use App\FacticalPrice;
use App\Services\FacticalPriceService;
use Illuminate\Console\Command;

class MakeFactPricesForUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:fact_prices_for_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        \Log::debug('MakeFactPricesForUsers started');
        $user = User::doesntHave('factPrice')->where('email', '!=', "")->orderBy('id', 'desc')->first();
        if ($user) {
            if (FacticalPrice::first()) {
                $products = Product::whereHas('factPrice')->get();
            } else {
                $products = Product::all();
            }

            \Log::debug($products->count());
            foreach ($products as $product) {
                (new FacticalPriceService)->updateFacticalPrice($product, $user);
            }
            return true;
        } else {
            \Log::debug('0 user who don\'t have fact price');
            $products = Product::doesntHave('factPrice')->whereHas('prices')->get();
            foreach ($products as $product) {
//                \Log::debug($product);
                if ($product) {
                    $users = User::where('email', '!=', "")->get();
                    \Log::debug($users->count());
                    foreach ($users as $user) {
                        (new FacticalPriceService)->updateFacticalPrice($product, $user);
                    }
                }
            }
        }

        \Log::debug('MakeFactPricesForUsers finished');
    }
}
