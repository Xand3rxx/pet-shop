<?php

namespace App\Traits;

trait AdminAccess
{
    /**
     * Prevent Administrator user from being deleted or edited.
     *
     * @param  int $role
     * @return boolean
     */
    public function preventFromDBeingDeletedOrEdited(int $role)
    {
        return ($role == \App\Models\User::ROLE['Admin']) ? true : false;
    }
}
