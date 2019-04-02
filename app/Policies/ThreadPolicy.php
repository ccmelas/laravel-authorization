<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Allows a user to update a thread if the user is a moderator and
     * the creator of the thread
     *
     * @param User $user
     * @param Thread $thread
     * @return bool
     */
    public function update(User $user, Thread $thread)
    {
        return $user->role === "moderator" && $thread->user_id === $user->id;
    }
}
