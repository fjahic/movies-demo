<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait AuthenticateApi
{
    private function logIn(?User $user = null): string
    {
        return Auth::guard('api')->login($user ?? User::factory()->create());
    }

    private function getAuthorizationHeader(?User $user = null): array
    {
        return ['authorization' => "Bearer {$this->logIn($user)}"];
    }
}