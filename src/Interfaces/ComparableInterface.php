<?php

namespace Atatusoft\Termutil\Interfaces;

/**
 * Interface for objects that can be compared to each other.
 *
 * @package Atatusoft\Termutil\Interfaces
 */
interface ComparableInterface extends EquatableInterface
{
    /**
     * Compares this object with the specified object for order.
     *
     * @param ComparableInterface $other The object to be compared.
     * @return int
     */
    public function compareTo(ComparableInterface $other): int;

    /**
     * Compares this object with the specified object for order.
     *
     * @param ComparableInterface $other The object to be compared.
     * @return bool True if this object is greater than the specified object, false otherwise.
     */
    public function greaterThan(ComparableInterface $other): bool;

    /**
     * Compares this object with the specified object for order.
     *
     * @param ComparableInterface $other The object to be compared.
     * @return bool True if this object is greater than or equal to the specified object, false otherwise.
     */
    public function greaterThanOrEqual(ComparableInterface $other): bool;

    /**
     * Compares this object with the specified object for order.
     *
     * @param ComparableInterface $other The object to be compared.
     * @return bool True if this object is less than the specified object, false otherwise.
     */
    public function lessThan(ComparableInterface $other): bool;

    /**
     * Compares this object with the specified object for order.
     *
     * @param ComparableInterface $other The object to be compared.
     * @return bool True if this object is less than or equal to the specified object, false otherwise.
     */
    public function lessThanOrEqual(ComparableInterface $other): bool;
}