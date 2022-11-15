<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Diler extends Model
{
    protected $table = 'dilers';

    protected $fillable = [
        'name',
        'is_active'
    ];

    public static function getDilerUser(int $diler_id)
    {
        $result = collect(DB::select('SELECT * FROM `users` INNER JOIN `dilers` ON `users`.`diler_id` = `dilers`.`id` 
            WHERE `users`.`diler_id` = '.$diler_id.' AND `users`.`id` NOT IN (SELECT `id` FROM `dilers`)'))->all();

        return User::hydrate($result);
    }

    public static function addDiler(array $diler = []): void
    {
        DB::table(self::$table)->insert($diler);
    }
}
