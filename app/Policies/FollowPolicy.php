<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Follow;
use App\Models\User;

class FollowPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Follow $follow): bool
    {
        return $user->id === $follow->user->id;
    }
}
