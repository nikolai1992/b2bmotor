<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\OrderModel;
use App\Product;

/**
 * App\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read \App\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Order sort(\Illuminate\Http\Request $request)
 */
class Order extends Model
{
    use OrderModel;

    private const STATUS_CREATED = 1;
    private const STATUS_ACTIVE = 2;
    private const STATUS_CANCELED = 3;

    private $statuses = [
        self::STATUS_CREATED => 'created',
        self::STATUS_ACTIVE => 'active',
        self::STATUS_CANCELED => 'canceled'
    ];

    protected $fillable = [
        'user_id', 'count', 'cost', 'status', 'comment'
    ];

    public function getStatus()
    {
        return $this->statuses[$this->status];
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'order_products')
            ->withPivot('qty')
            ->withTimestamps();
    }

    public function total()
    {
        return collect(DB::select('SELECT SUM(`products_total`.`total`) as cost, SUM(`products_total`.`qty`) as qty
                                        FROM (SELECT `order_products`.`order_id`, `order_products`.`qty` AS qty,  `products`.`price` * `order_products`.`qty` AS total
                                        FROM `products` INNER JOIN `order_products` ON `products`.`id` = `order_products`.`product_id`
                                        WHERE `order_products`.`order_id` = :id) AS `products_total`', ['id' => $this->id]))->first();
    }

    public function updateProducts(array $products = [])
    {
        foreach ($products as $key => $value) {
            DB::table('order_products')
                ->where('product_id', $key)
                ->update(['qty' => $value['qty']]);
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function data1c($data)
    {
        /* API URL */
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://d83a0c025031.sn.mynetname.net:60015/orders/hs/order',
           // CURLOPT_URL => 'http://d83a0c025031.sn.mynetname.net:60015/orders_prod/hs/order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic QjJCX0ludGFncmF0aW9uOm1vdG9yQjJCaW50MTUw'
            ),
        ));

        $responseCurl = curl_exec($curl);
       // $response = curl_exec($curl);

        curl_close($curl);
        //$response=json_decode($responseCurl,true);
        return $responseCurl;
    }

    public function getNewOrderProductFromUUID($product)
    {
        $productIdList = [];
        $productUuidAmount = [];
        foreach ($product as $item) {
            $productIdList[] = $item['uuid'];
            $productUuidAmount[$item['uuid']] = $item['qty'];
        }
        $products = Product::whereIn('uuid', $productIdList)->get();
        $productIdAmount = [];
        foreach ($products->all() as $item) {
            $productIdAmount[$item->id]['qty'] = $productUuidAmount[$item->uuid];
        }
        return [$productIdAmount];
    }
}
