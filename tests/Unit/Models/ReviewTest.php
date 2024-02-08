<?php

namespace Tests\Unit\Models;

use App\Models\Follow;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function review_belongs_to_a_user(): void
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(User::class, $review->user);
    }

    #[Test]
    public function review_belongs_to_a_movie(): void
    {
        $review = Review::factory()->create();

        $this->assertInstanceOf(Movie::class, $review->movie);
    }
}
