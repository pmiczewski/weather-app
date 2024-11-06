<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LocationPolicy
{
    public function create(User $user): Response
    {
        $locationCount = Location::query()
            ->where('user_id', $user->id)
            ->count();

        if($locationCount >= 3) {
            return Response::deny('You can have up to 3 saved locations!');
        }

        return Response::allow();
    }
}
