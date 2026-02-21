<?php

namespace Atatusoft\Termutil\Events;

use Atatusoft\Termutil\Events\Event;

class MouseEvent extends Event
{
    public readonly int $buttonIndex;
    public readonly string $buttonName;
    public readonly string $x;
    public readonly string $y;
    public readonly bool $isRelease;
    public readonly string $action;

    /**
     * @param string $escapeSequence The ANSI escape sequence, SGR sequences are roughly 10-15 chars long
     */
    public function __construct(string $escapeSequence)
    {
        parent::__construct("mouse-event", null, $escapeSequence);

        // Regex for SGR 1006 format: \033[< BUTTON ; X ; Y M/m
        // M = Press/Move, m = Release
        if (preg_match('/\033\[<(\d+);(\d+);(\d+)([Mm])/', $escapeSequence, $matches)) {
            $this->buttonIndex = (int)$matches[1];
            $this->x = (int)$matches[2];
            $this->y = (int)$matches[3];
            $this->isRelease = ($matches[4] === 'm');

            // Simple mapping for common buttons
            $this->action = $this->isRelease ? "Released" : "Pressed/Moving";
            $this->buttonName = match ($this->buttonIndex) {
                0 => "Left Click",
                1 => "Middle Click",
                2 => "Right Click",
                32 => "Motion (Left Down)",
                35 => "Motion (No Button)",
                default => "Button $this->buttonIndex",
            };
        }

    }
}