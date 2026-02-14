<?php

namespace Atatusoft\Termutil\UI\Windows\Interfaces;

use Atatusoft\Termutil\Events\Interfaces\ObservableInterface;
use Atatusoft\Termutil\Interfaces\RenderableInterface;
use Atatusoft\Termutil\UI\Windows\BorderPack;

interface WindowInterface extends RenderableInterface, ObservableInterface
{
    public string $title { get; set; }
    public string $help { get; set; }
    public array $position { get; set; }
    public BorderPack $borderPack { get; set; }
}