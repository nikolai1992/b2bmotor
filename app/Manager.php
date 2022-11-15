<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manager extends Model
{
    public function client()
    {
        return $this->belongsTo('App\User', 'client_id', 'id');
    }

    public static function getClientsWithoutManager()
    {
        $result = collect(DB::select('SELECT * FROM `users` INNER JOIN `role_users` ON `users`.`id` = `role_users`.`user_id` 
            WHERE `role_users`.`role_id` = 3 AND `users`.`id` NOT IN (SELECT `client_id` FROM `managers`)'))->all();

        return User::hydrate($result);
    }

    public static function addClients(array $clients = []): void
    {
        DB::table('managers')->insert($clients);
    }

    public static function getClients(int $id): array
    {
        $clients = Manager::where('manager_id', '=', $id)->get(['client_id']);

        return array_map(function ($item) {
            return $item['client_id'];
        }, $clients->toArray());
    }
}
