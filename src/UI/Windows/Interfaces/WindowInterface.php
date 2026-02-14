<?php

namespace Atatusoft\Termutil\UI\Windows\Interfaces;

use Atatusoft\Termutil\Events\Interfaces\ObservableInterface;
use Atatusoft\Termutil\Interfaces\RenderableInterface;
use Atatusoft\Termutil\IO\Enumerations\Color;
use Atatusoft\Termutil\UI\Windows\BorderPack;
use Atatusoft\Termutil\UI\Windows\WindowAlignment;

interface WindowInterface extends RenderableInterface, ObservableInterface
{
    public string $title { get; set; }
    public string $help { get; set; }
    public array $position { get; set; }
    public BorderPack $borderPack { get; set; }
    public WindowAlignment $alignment { get; set; }
    public Color $backgroundColor { get; set; }
    public ?Color $foregroundColor { get; set; }
    public array $content { get; set; }
}