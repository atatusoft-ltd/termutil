<?php

namespace Atatusoft\Termutil\UI\Windows\Enumerations;

enum WindowPosition
{
    case TOP;
    case MIDDLE;
    case BOTTOM;

    /**
     * Gets the coordinates of the window.
     *
     * @param int $windowWidth The width of the window.
     * @param int $windowHeight The height of the window.
     *
     * @return array{x: int, y: int} Returns the coordinates of the window.
     */
    public function getCoordinates(int $windowWidth, int $windowHeight, int $terminalWidth = DEFAULT_TERMINAL_WIDTH, int $terminalHeight = DEFAULT_TERMINAL_HEIGHT): array
    {
        $leftMargin = (int)( ($terminalWidth / 2) - ($windowWidth / 2) );
        $middleAlignedTopMargin = (int)( ($terminalHeight / 2) - ($windowHeight / 2) );
        $bottomAlignedTopMargin = $terminalHeight - $windowHeight - 1;

        return match ($this) {
            self::TOP => [ "x" => $leftMargin, "y" => 1],
            self::MIDDLE => [ "x" => $leftMargin, "y" => $middleAlignedTopMargin],
            self::BOTTOM => [ "x" => $leftMargin, "y" => $bottomAlignedTopMargin],
        };
    }
}
