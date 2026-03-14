<?php

use Atatusoft\Termutil\IO\Console\Console;
use Atatusoft\Termutil\IO\Enumerations\MouseTrackingMode;

it("emits a valid sgr mouse reporting sequence", function (): void {
    expect(MouseTrackingMode::ALL_MOTION_TRACKING->getSequence())
        ->toBe("\033[?1003;1006h");
});

it("disables all supported mouse tracking modes", function (): void {
    ob_start();
    Console::disableMouseReporting();
    $output = ob_get_clean();

    expect($output)->toBe("\033[?1000;1002;1003;1006l");
});
