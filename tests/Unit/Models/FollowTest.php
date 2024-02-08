<?php

namespace Models;

use App\Models\Follow;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function follow_belongs_to_a_user(): void
    {
        $follow = Follow::factory()->create();

        $this->assertInstanceOf(User::class, $follow->user);
    }

    #[Test]
    public function follow_belongs_to_a_movie(): void
    {
        $follow = Follow::factory()->create();

        $this->assertInstanceOf(Movie::class, $follow->movie);
    }
}
