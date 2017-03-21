<?php

namespace App\Policies;

use App\User;
use App\Models\Rollcall;
use Illuminate\Auth\Access\HandlesAuthorization;

class RollcallPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the rollcall.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rollcall  $rollcall
     * @return mixed
     */
    public function view(User $user, Rollcall $rollcall)
    {
        //
    }

    /**
     * Determine whether the user can create rollcalls.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create rollcalls at specific date.
     *
     * @param  \App\User $user
     * @param  string    $date    formatted date Y-m-d
     * @return boolean
     */
    public function createAt(User $user, $date)
    {
        return true;
    }

    /**
     * Determine whether the user can update the rollcall.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rollcall  $rollcall
     * @return mixed
     */
    public function update(User $user, Rollcall $rollcall)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the rollcall.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Rollcall  $rollcall
     * @return mixed
     */
    public function delete(User $user, Rollcall $rollcall)
    {
        //
    }
}
