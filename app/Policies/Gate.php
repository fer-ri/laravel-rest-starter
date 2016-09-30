<?php

namespace App\Policies;

class Gate extends \Illuminate\Auth\Access\Gate
{
    /**
     * Return abilities.
     *
     * @return array
     */
    public function getAbilities()
    {
        return $this->abilities;
    }

    /**
     * Return policies.
     *
     * @return array
     */
    public function getPolicies()
    {
        return $this->policies;
    }
}
