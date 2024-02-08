<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    public function apply(Builder $query, string $value): Builder;
}