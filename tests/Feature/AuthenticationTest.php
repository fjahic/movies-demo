<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AuthenticateApi;

class AuthenticationTest extends TestCase
{
    use AuthenticateApi;
    use RefreshDatabase;

    #[Test]
    public function user_can_login(): void
    {
        $user = User::factory()->state(['password' => 'Password123456'])->create();

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'Password123456',
        ], ['Accept' => 'application/json']);

        $response->assertSuccessful()->assertJson(fn(AssertableJson $json) => $json->has('token')->etc());
    }

    #[Test]
    public function user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->state(['password' => 'Password123456'])->create();

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'Password654321',
        ], ['Accept' => 'application/json']);

        $response->assertUnauthorized();
    }

    #[Test]
    public function user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.logout'), $this->getAuthorizationHeader($user));

        $response->assertSuccessful();
        $this->assertNull(Auth::guard('api')->user());
    }

    #[Test]
    public function user_can_refresh_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.refresh'), $this->getAuthorizationHeader($user));

        $response->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json->has('token')->etc());
    }
}
