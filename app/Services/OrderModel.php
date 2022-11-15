<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait OrderModel
{
    public function scopeSort(Builder $queryBuilder, Request $request): Builder
    {
        return $queryBuilder->orderBy(
            $request->query('sort_by', 'id'),
            $request->query('sort_dir', 'asc')
        );
    }
}
