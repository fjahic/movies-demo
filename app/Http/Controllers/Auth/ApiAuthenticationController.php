<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class ApiAuthenticationController extends Controller
{
    public function store(ApiLoginRequest $request)
    {
        /** @var string|false $token */
        $token = Auth::guard('api')->attempt($request->validated());

        if (!$token) {
            abort(401);
        }

        return $this->tokenResponse($token);
    }

    public function destroy(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->successResponse();
    }

    public function refresh(): JsonResponse
    {
        return $this->tokenResponse(Auth::guard('api')->refresh());
    }

    private function tokenResponse(string $token): JsonResponse
    {
        return Response::json([
            'token' => $token,
            'type' => 'Bearer',
            'expires' => Config::get('jwt.ttl') * 60,
        ]);
    }
}