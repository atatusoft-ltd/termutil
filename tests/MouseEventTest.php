<?php

use Atatusoft\Termutil\Events\MouseEvent;
use Atatusoft\Termutil\IO\Mouse\Enumerations\MouseButton;

it("parses button presses in sgr mode", function (): void {
    $event = new MouseEvent("\033[<0;10;20M");

    expect($event->rawCode)->toBe(0)
        ->and($event->button)->toBe(MouseButton::LEFT_BUTTON)
        ->and($event->buttonIndex)->toBe(MouseButton::LEFT_BUTTON->value)
        ->and($event->buttonName)->toBe("Left Button")
        ->and($event->x)->toBe(10)
        ->and($event->y)->toBe(20)
        ->and($event->isRelease)->toBeFalse()
        ->and($event->isMotion)->toBeFalse()
        ->and($event->isWheel)->toBeFalse()
        ->and($event->action)->toBe("Pressed");
});

it("parses drag and modifier flags", function (): void {
    $event = new MouseEvent("\033[<52;4;7M");

    expect($event->rawCode)->toBe(52)
        ->and($event->button)->toBe(MouseButton::LEFT_BUTTON)
        ->and($event->isMotion)->toBeTrue()
        ->and($event->isCtrlPressed)->toBeTrue()
        ->and($event->action)->toBe("Dragged");
});

it("parses hover movement with no pressed button", function (): void {
    $event = new MouseEvent("\033[<35;30;40M");

    expect($event->button)->toBe(MouseButton::NO_BUTTON)
        ->and($event->buttonName)->toBe("No Button")
        ->and($event->isMotion)->toBeTrue()
        ->and($event->action)->toBe("Moved");
});

it("parses wheel events", function (): void {
    $event = new MouseEvent("\033[<65;12;8M");

    expect($event->button)->toBe(MouseButton::SCROLL_DOWN)
        ->and($event->isWheel)->toBeTrue()
        ->and($event->action)->toBe("Scrolled");
});

it("parses release events", function (): void {
    $event = new MouseEvent("\033[<0;10;20m");

    expect($event->button)->toBe(MouseButton::LEFT_BUTTON)
        ->and($event->isRelease)->toBeTrue()
        ->and($event->action)->toBe("Released");
});

it("rejects malformed mouse sequences", function (): void {
    new MouseEvent("\033[A");
})->throws(InvalidArgumentException::class);
