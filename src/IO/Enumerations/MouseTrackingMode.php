<?php

namespace Atatusoft\Termutil\IO\Enumerations;

/**
 * An enumeration of ANSI mouse reporting modes for capturing mouse input in a terminal.
 * Once enabled the terminal will send input sequences to your program's stdin whenever a
 * mouse event occurs.
 */
enum MouseTrackingMode: int
{
    case STANDARD_CLICK = 1000;
    case CELL_MOTION_TRACKING = 1002;
    case ALL_MOTION_TRACKING = 1003;

    /**
     * Returns the ANSI escape sequence for the corresponding mode.
     *
     * @param bool $withSGRExtendedMode
     * @return string
     */
    public function getSequence(bool $withSGRExtendedMode = true): string
    {
        $sequence = "\033[?{$this->value}";

        if ($withSGRExtendedMode) {
            $sequence .= ";1006";
        }

        return "{$sequence}h";
    }
}
