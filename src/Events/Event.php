<?php

namespace Atatusoft\Termutil\Events;

use DateTimeImmutable;

/**
 * The Event class represents an event that takes place in the terminal.
 */
abstract class Event
{
    protected(set) DateTimeImmutable $timestamp;

    public function __construct(
        protected(set) string $type,
        protected(set) mixed $target,
        protected(set) mixed $data = null,
    )
    {
        $this->timestamp = new DateTimeImmutable();
    }
}