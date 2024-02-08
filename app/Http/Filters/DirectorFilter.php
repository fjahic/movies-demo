<?php

declare(strict_types=1);

namespace App\Http\Filters;

use App\Http\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

class DirectorFilter implements FilterInterface
{
    public function apply(Builder $query, string $value): Builder
    {
        return $query->where('director', $value);
    }
}