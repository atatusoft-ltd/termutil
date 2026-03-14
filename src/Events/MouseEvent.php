<?php

namespace Atatusoft\Termutil\Events;

use Atatusoft\Termutil\Events\Event;
use Atatusoft\Termutil\IO\Mouse\Enumerations\MouseButton;
use InvalidArgumentException;

class MouseEvent extends Event
{
    public readonly int $rawCode;
    public readonly int $buttonIndex;
    public readonly MouseButton $button;
    public readonly string $buttonName;
    public readonly int $x;
    public readonly int $y;
    public readonly bool $isRelease;
    public readonly bool $isMotion;
    public readonly bool $isWheel;
    public readonly bool $isShiftPressed;
    public readonly bool $isAltPressed;
    public readonly bool $isCtrlPressed;
    public readonly string $action;

    /**
     * @param string $escapeSequence The ANSI escape sequence, SGR sequences are roughly 10-15 chars long
     */
    public function __construct(string $escapeSequence)
    {
        parent::__construct("mouse-event", null, $escapeSequence);

        // Regex for SGR 1006 format: \033[< BUTTON ; X ; Y M/m
        // M = Press/Move, m = Release
        if (
            !preg_match('/^\033\[<(\d+);(\d+);(\d+)([Mm])$/', $escapeSequence, $matches)
        ) {
            throw new InvalidArgumentException(
                "Mouse events must be in SGR 1006 format."
            );
        }

        $this->rawCode = (int) $matches[1];
        $this->x = (int) $matches[2];
        $this->y = (int) $matches[3];
        $this->isRelease = ($matches[4] === 'm');
        $this->isShiftPressed = (bool) ($this->rawCode & 4);
        $this->isAltPressed = (bool) ($this->rawCode & 8);
        $this->isCtrlPressed = (bool) ($this->rawCode & 16);
        $this->isMotion = (bool) ($this->rawCode & 32);
        $this->isWheel = (bool) ($this->rawCode & 64);

        $buttonOffset = $this->rawCode & 0b11;
        $this->buttonIndex = $this->isWheel ? $buttonOffset + 4 : $buttonOffset;
        $this->button = MouseButton::from($this->buttonIndex);
        $this->buttonName = $this->button->label();
        $this->action = match (true) {
            $this->isWheel => "Scrolled",
            $this->isMotion && $this->button === MouseButton::NO_BUTTON => "Moved",
            $this->isMotion => "Dragged",
            $this->isRelease => "Released",
            default => "Pressed",
        };
    }
}
