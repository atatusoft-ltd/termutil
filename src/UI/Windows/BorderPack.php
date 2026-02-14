<?php

namespace Atatusoft\Termutil\UI\Windows;

/**
 * Represents a border pack. A border pack is a collection of characters that can be used to draw borders around windows.
 *
 * @package Atatusoft\Termutil\UI\Windows
 */
readonly class BorderPack
{
    /**
     * @param string $topLeft The character to use for the top left corner of the border.
     * @param string $topRight The character to use for the top right corner of the border.
     * @param string $bottomLeft The character to use for the bottom left corner of the border.
     * @param string $bottomRight The character to use for the bottom right corner of the border.
     * @param string $horizontal The character to use for the horizontal lines of the border.
     * @param string $vertical The character to use for the vertical lines of the border.
     * @param string $topTee The character to use for the top tee of the border.
     * @param string $bottomTee The character to use for the bottom tee of the border.
     * @param string $leftTee The character to use for the left tee of the border.
     * @param string $rightTee The character to use for the right tee of the border.
     * @param string $cross The character to use for the cross of the border.
     */
    public function __construct(
        public string $topLeft = '┌',
        public string $topRight = '┐',
        public string $bottomLeft = '└',
        public string $bottomRight = '┘',
        public string $horizontal = '─',
        public string $vertical = '│',
        public string $topTee = '┬',
        public string $bottomTee = '┴',
        public string $leftTee = '├',
        public string $rightTee = '┤',
        public string $cross = '┼'
    )
    {
    }
}