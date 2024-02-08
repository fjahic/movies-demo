<?php

namespace Models;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    #[Test]
    public function user_has_favourites(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->favourites);
    }

    #[Test]
    public function user_has_follows(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->follows);
    }

    #[Test]
    public function user_has_reviews(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->reviews);
    }
}
