<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

trait HasPaginatedCache
{
    protected function getPageCacheKey(string $prefix, int $page = 1): string
    {
        return "$prefix.user=" . Auth::id() . "page=$page";
    }

    private function forgetPageCaches(string $prefix, int $pages): void
    {
        for ($i = 1; $i <= $pages; $i++) {
            Cache::forget($this->getPageCacheKey($prefix, $i));
        }
    }
}