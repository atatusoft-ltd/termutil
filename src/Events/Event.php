<?php

namespace Atatusoft\Termutil\Events;

use DateTimeImmutable;

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