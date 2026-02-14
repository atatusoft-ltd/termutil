<?php

namespace Atatusoft\Termutil\UI\Windows;

/**
 *
 */
final readonly class WindowPadding
{
    /**
     * WindowPadding constructor.
     *
     * @param int $topPadding The padding at the top of the window.
     * @param int $rightPadding The padding at the right of the window.
     * @param int $bottomPadding The padding at the bottom of the window.
     * @param int $leftPadding The padding at the left of the window.
     */
    public function __construct(
        public int $topPadding = 0,
        public int $rightPadding = 0,
        public int $bottomPadding = 0,
        public int $leftPadding = 0
    )
    {
    }
}