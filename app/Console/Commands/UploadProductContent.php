<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;
use App\WaitingForDownload;
use App\Services\Product\ProductServiceImpl;
use DB;

class UploadProductContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:product_content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloading product content';

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
        \Log::debug('UploadProductContent started');
        ini_set('memory_limit', '-1');
        $queues = WaitingForDownload::all();
        foreach ($queues as $queue) {
            DB::transaction(function () use ($queue) {
                \Log::debug("UploadProductContent product_id = ".$queue->product_id);
                \Log::debug($queue);
                if ($queue) {
                    (new ProductServiceImpl())->downloadProductContent($queue);
                    \Log::debug("Finish downloadProductContent function");
                    $queue->delete();
                }
            });
        }
        $products = Product::where("thumb", '/images/no-image-icon.png')->where('images_request', '!=', '')->whereDoesntHave('uploadingQueue')
            ->orWhere('files_request', '!=', '')->whereDoesntHave('files')->whereDoesntHave('uploadingQueue')->get();

        foreach ($products as $product) {
            $new_files_path = $product->files_request ? json_decode($product->files_request) : '';
            (new ProductServiceImpl())->checkOnAddingNewQueue($product, $product->images_request, $new_files_path);
        }

        \Log::debug('UploadProductContent finished');
    }
}
