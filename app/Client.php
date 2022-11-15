<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    protected $fillable = [
        'client_id',
        'category_id',
    ];

    public static function addCategories(array $categories = [], int $clientId): void
    {
        Client::where('client_id', $clientId)->delete();

        $categories = array_map(function ($item) use ($clientId) {
            return ['client_id' => $clientId, 'category_id' => $item];
        }, $categories);

        DB::table('clients')->insert($categories);
    }

    public static function getDenies(): array
    {
        $user_id = Auth::user()->id;
        $deny = self::where('client_id', $user_id)->get(['category_id']);

        return array_map(function ($item) {
            return $item['category_id'];
        }, $deny->toArray());
    }
}
