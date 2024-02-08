<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Favorite;
use App\Models\User;

class FavoritePolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Favorite $favorite): bool
    {
        return $user->id === $favorite->user->id;
    }
}
