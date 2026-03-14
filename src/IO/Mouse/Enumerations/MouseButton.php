<?php

namespace Atatusoft\Termutil\IO\Mouse\Enumerations;

enum MouseButton: int
{
    case LEFT_BUTTON = 0;
    case MIDDLE_BUTTON = 1;
    case RIGHT_BUTTON = 2;
    case NO_BUTTON = 3;
    case SCROLL_UP = 4;
    case SCROLL_DOWN = 5;
    case SCROLL_LEFT = 6;
    case SCROLL_RIGHT = 7;

    public function label(): string
    {
        return match ($this) {
            self::LEFT_BUTTON => "Left Button",
            self::MIDDLE_BUTTON => "Middle Button",
            self::RIGHT_BUTTON => "Right Button",
            self::NO_BUTTON => "No Button",
            self::SCROLL_UP => "Scroll Up",
            self::SCROLL_DOWN => "Scroll Down",
            self::SCROLL_LEFT => "Scroll Left",
            self::SCROLL_RIGHT => "Scroll Right",
        };
    }
}
