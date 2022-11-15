<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UpdateProductPrice;
use App\Services\Product\ProductServiceImpl;
use DB;

class UpdateProductsPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product_price';

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
        \Log::debug('UpdateProductsPrice started');
        $update_products = UpdateProductPrice::all();
        foreach ($update_products as $update_product) {
            $product = $update_product->product;
            $request = json_decode($update_product->request);
            DB::transaction(function () use ($request, $product, $update_product) {
                (new ProductServiceImpl)->updatePrices($product, $request);
                $product->total_price = $product->findSmallestPrice();
                $product->save();
                $update_product->delete();
            });
        }
        \Log::debug('UpdateProductsPrice finished');
    }
}
