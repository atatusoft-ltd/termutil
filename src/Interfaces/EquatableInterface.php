<?php

namespace Atatusoft\Termutil\Interfaces;

interface EquatableInterface
{
    /**
     * Checks if the given value is equal to this value.
     *
     * @param EquatableInterface $equatable The value to check.
     * @return bool True if the given value is equal to this value, false otherwise.
     */
    public function equals(EquatableInterface $equatable): bool;

    /**
     * Checks if the given value is not equal to this value.
     *
     * @param EquatableInterface $equatable The value to check.
     * @return bool True if the given value is not equal to this value, false otherwise.
     */
    public function notEquals(EquatableInterface $equatable): bool;

    public string $hash {
        get;
    }
}