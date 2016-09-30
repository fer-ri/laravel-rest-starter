<?php

namespace App\Policies;

abstract class AbstractPolicy
{
    /**
     * Get friendly name for this policy.
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Get all permissions for this policy.
     *
     * @return array Pair of name and display name
     */
    abstract public function getPermissions();
}
