<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** @property string $slug */
trait HasSlug
{
    abstract protected function getSluggableValue(): string;

    protected static function bootHasSlug(): void
    {
        static::creating(fn(self $model) => $model->setAttribute('slug', Str::slug($model->getSluggableValue())));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}