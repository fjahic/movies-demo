<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'storyline',
        'director',
        'release_year',
    ];

    protected $with = ['reviews'];

    protected function getSluggableValue(): string
    {
        return $this->title;
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function follows(): HasMany
    {
        return $this->hasMany(Follow::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function latestReviews(): HasMany
    {
        return $this->hasMany(Review::class)->limit(3)->latest();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function rating(): Attribute
    {
        return new Attribute(get: fn() => $this->reviews->sum('rating') / 10);
    }
}
