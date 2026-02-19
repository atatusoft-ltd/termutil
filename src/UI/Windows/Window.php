<?php

namespace Atatusoft\Termutil\UI\Windows;

use Assegai\Collections\ItemList;
use Atatusoft\Termutil\Events\Event;
use Atatusoft\Termutil\Events\Interfaces\ObservableInterface;
use Atatusoft\Termutil\Events\Interfaces\ObserverInterface;
use Atatusoft\Termutil\Events\Interfaces\StaticObserverInterface;
use Atatusoft\Termutil\Events\Traits\ObservableTrait;
use Atatusoft\Termutil\IO\Console\Console;
use Atatusoft\Termutil\IO\Console\Cursor;
use Atatusoft\Termutil\IO\Enumerations\Color;
use Atatusoft\Termutil\UI\Windows\Enumerations\HorizontalAlignment;
use Atatusoft\Termutil\UI\Windows\Enumerations\VerticalAlignment;
use Atatusoft\Termutil\UI\Windows\Interfaces\WindowInterface;

/**
 * Class Window. Represents a window in the console. It can be rendered and erased at a specific position. It also
 * supports borders, padding, and alignment of content.
 *
 * @package Atatusoft\Termutil\UI\Windows
 */
class Window implements WindowInterface
{
    use ObservableTrait;

    public const int DEFAULT_WINDOW_WIDTH = 80;
    public const int DEFAULT_WINDOW_HEIGHT = 30;
    /**
     * @var string The title of the window. This is rendered in the top border of the window and can contain any
     * text or information that needs to be displayed.
     */
    public string $title {
        get {
            return $this->title;
        }
        set {
            $this->title = $value;
        }
    }
    /**
     * @var string The help text of the window. This is rendered in the bottom border of the window and
     * can contain any text or
     */
    public string $help {
        get {
            return $this->help;
        }
        set {
            $this->help = $value;
        }
    }
    public array $position {
        get {
            return $this->position;
        }
        set {
            $this->position = $value;
        }
    }
    public BorderPack $borderPack {
        get {
            return $this->borderPack;
        }
        set {
            $this->borderPack = $value;
        }
    }
    public WindowAlignment $alignment {
        get {
            return $this->alignment;
        }
        set {
            $this->alignment = $value;
        }
    }
    /**
     * @var WindowPadding The padding of the window. This is used to render the content of the window with a
     * specific amount of padding on each side. The padding is applied to the content area of the window, and does not affect the borders or the background color.
     */
    public WindowPadding $padding {
        get {
            return $this->padding;
        }
        set {
            $this->padding = $value;
        }
    }
    /**
     * @var Color The background color of the window. This is used to render the background of the window in a
     * specific color. The background color is applied to the entire window, including the borders and content area.
     */
    public Color $backgroundColor {
        get {
            return $this->backgroundColor;
        }
        set {
            $this->backgroundColor = $value;
        }
    }
    /**
     * @var Color|null The foreground color of the window. This is used to render the text and borders of the
     * window in a specific color. If null, the default console color is used.
     */
    public ?Color $foregroundColor {
        get {
            return $this->foregroundColor;
        }
        set {
            $this->foregroundColor = $value;
        }
    }
    /**
     * @var string[] The content of the window. This is rendered in the content area of the window and can contain
     * any text or information that needs to be displayed.
     */
    public array $content {
        get {
            return $this->content;
        }
        set {
            $this->content = $value;
        }
    }
    /**
     * @var string The top border of the window. This is rendered at the top of the window and can contain the title or other information.
     */
    public string $topBorder {
        get {
            $titleLength = strlen($this->title);
            $borderLength = $this->width - $titleLength - 3; // Subtracting 3 for the spaces and border character
            $output = $this->borderPack->topLeft . $this->borderPack->horizontal . $this->title;
            $output .= str_repeat($this->borderPack->horizontal, $borderLength);
            $output .= $this->borderPack->topRight;

            if ($this->foregroundColor) {
                return $this->foregroundColor->value . $output . Color::RESET->value;
            }

            return $output;
        }
    }
    /**
     * @var string The bottom border of the window. This is rendered at the bottom of the window and can contain help text or other information.
     */
    public string $bottomBorder {
        get {
            $helpLength = strlen($this->help);
            $output = $this->borderPack->bottomLeft . $this->borderPack->horizontal . $this->help;
            $output .= str_repeat($this->borderPack->horizontal, $this->width - $helpLength - 3);
            $output .= $this->borderPack->bottomRight;

            if ($this->foregroundColor) {
                return $this->foregroundColor->value . $output . Color::RESET->value;
            }

            return $output;
        }
    }
    /**
     * @var ItemList<ObserverInterface> The observers of the window. These are notified when an event occurs in the
     * window, such as a key press or mouse click.
     */
    protected ItemList $observers;
    /**
     * @var ItemList<StaticObserverInterface> The static observers of the window. These are notified when an event
     * occurs in the window, such as a key press or mouse click. Static observers are called on the class itself,
     * rather than on an instance of the class.
     */
    protected ItemList $staticObservers;
    protected Cursor $cursor;
    /**
     * @var array The left aligned content of the window. This is rendered in the content area of the window and is
     * aligned to the left. The content is padded with spaces based on the padding settings of the window.
     */
    protected array $leftAlignedContent {
        get {
            $content = [];

            foreach ($this->content as $lineOfContent) {
                $contentLength = mb_strlen($lineOfContent);
                $leftPaddingLength = $this->padding->leftPadding;
                $rightPaddingLength = $this->width - $contentLength - $this->padding->rightPadding - 2;

                $output = $this->borderPack->vertical;
                $output .= str_repeat(' ', (int)max($leftPaddingLength, 0));
                $output .= $lineOfContent;
                $output .= str_repeat(' ', (int)max($rightPaddingLength, 0));
                $output .= $this->borderPack->vertical;

                $content[] = $output;
            }

            return $content;
        }
    }

    protected array $centerAlignedContent {
        get {
            $content = [];

            foreach ($this->content as $lineOfContent)
            {
                $contentLength = mb_strlen($lineOfContent);
                $totalPadding = $this->width - $this->padding->leftPadding - $contentLength - $this->padding->rightPadding - 2;
                $leftPaddingLength = max(floor($totalPadding / 2), 0);
                $rightPaddingLength = max(ceil($totalPadding / 2), 0);

                $output = $this->borderPack->vertical;
                $contentRender = str_repeat(' ', (int)max($leftPaddingLength, 0));
                $contentRender .= $lineOfContent;
                $contentRender .= str_repeat(' ', (int)max($rightPaddingLength, 0));

                $output .= str_pad($contentRender, $this->width - 2, ' ', STR_PAD_BOTH);
                $output .= $this->borderPack->vertical;

                $content[] = $output;
            }

            return $content;
        }
    }

    protected array $rightAlignedContent {
        get {
            $content = [];

            foreach ($this->content as $lineOfContent)
            {
                $contentLength = mb_strlen($lineOfContent);
                $leftPaddingLength = $this->width - $contentLength - $this->padding->leftPadding - 2;
                $rightPaddingLength = $this->padding->rightPadding; // -1 for the border

                $output = $this->borderPack->vertical;
                $output .= str_repeat(' ', (int)max($leftPaddingLength, 0));
                $output .= $lineOfContent;
                $output .= str_repeat(' ', (int)max($rightPaddingLength, 0));
                $output .= $this->borderPack->vertical;

                $content[] = $output;
            }

            return $content;
        }
    }

    protected array $linesOfContent {
        get {
            $content = [];

            for($row = 0; $row < $this->padding->topPadding; $row++) {
                $output = $this->borderPack->vertical;
                $output .= str_repeat(' ', $this->width - 2);
                $output .= $this->borderPack->vertical;
                $content[] = $output;
            }

            $alignedContent = match($this->alignment->horizontalAlignment) {
                HorizontalAlignment::LEFT => $this->leftAlignedContent,
                HorizontalAlignment::CENTER => $this->centerAlignedContent,
                HorizontalAlignment::RIGHT => $this->rightAlignedContent,
            };

            foreach ($alignedContent as $lineOfContent) {
                if ($this->foregroundColor) {
                    $content[] = $this->foregroundColor->value . $lineOfContent . Color::RESET->value;
                } else {
                    $content[] = $lineOfContent;
                }
            }

            // TODO: Implement dynamic heigh adjustment vs clipping behaviour
            $paddingLineCount = max(0, $this->innerHeight - count($content));

            for ($line = 0; $line < $paddingLineCount; $line++) {
                $content[] = $this->borderPack->vertical . str_repeat(' ', $this->innerWidth) . $this->borderPack->vertical;
            }

            for ($row = 0; $row < $this->padding->bottomPadding; $row++) {
                $output = $this->borderPack->vertical;
                $output .= str_repeat(' ', $this->width - 2);
                $output .= $this->borderPack->vertical;
                $content[] = $output;
            }

            return $content;
        }
    }
    /**
     * @var int The width minus the left and right borders.
     */
    public int $innerWidth {
        get {
            return $this->width - 2;
        }
    }
    /**
     * @var int The height minus the top and bottom borders.
     */
    public int $innerHeight {
        get {
            return $this->height - 2;
        }
    }

    /**
     * @param string $title
     * @param string $help
     * @param array $position
     * @param int $width
     * @param int $height
     * @param BorderPack $borderPack
     * @param WindowAlignment $alignment
     * @param WindowPadding $padding
     * @param Color $backgroundColor
     * @param Color|null $foregroundColor
     * @param array $content
     */
    public function __construct(
        string $title = '',
        string $help = '',
        array $position = [0, 0],
        protected int $width = self::DEFAULT_WINDOW_WIDTH,
        protected int $height = self::DEFAULT_WINDOW_HEIGHT,
        BorderPack $borderPack = new BorderPack(),
        WindowAlignment $alignment = new WindowAlignment(HorizontalAlignment::LEFT, VerticalAlignment::MIDDLE),
        WindowPadding $padding = new WindowPadding(rightPadding: 1, leftPadding: 1),
        Color $backgroundColor = Color::BLACK,
        ?Color $foregroundColor = null,
        array $content = []
    )
    {
        $this->title = $title;
        $this->help = $help;
        $this->position = $position;
        $this->borderPack = $borderPack;
        $this->alignment = $alignment;
        $this->padding = $padding;
        $this->backgroundColor = $backgroundColor;
        $this->foregroundColor = $foregroundColor;
        $this->content = $content;

        $this->cursor = Console::cursor();
        $this->observers = new ItemList(ObserverInterface::class);
        $this->staticObservers = new ItemList(StaticObserverInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        $this->renderAt(0, 0);
    }

    /**
     * @inheritDoc
     */
    public function renderAt(?int $x = null, ?int $y = null): void
    {
        $position = $this->position;
        $positionX = $position["x"] ?? $position[0];
        $positionY = $position["y"] ?? $position[1];

        $leftMargin = $positionX + ($x ?? 0);
        $topMargin = $positionY + ($y ?? 0);

        // Render the top border
        $topBorderHeight = 1;
        $output = $this->topBorder;
        $this->cursor->moveTo($leftMargin, $topMargin);
        echo $output;

        // Render content
        $linesOfContent = $this->linesOfContent;
        if (!$linesOfContent) {
            $linesOfContent = [''];
        }

        foreach ($linesOfContent as $index => $line) {
            $this->cursor->moveTo($leftMargin, $topMargin + $index + $topBorderHeight);
            echo mb_substr($line, 0, $this->width);
        }

        // Render the bottom border
        $topMargin = $topMargin + count($linesOfContent) + $topBorderHeight;
        $output = $this->bottomBorder;
        $this->cursor->moveTo($leftMargin, $topMargin);
        echo $output;
    }

    /**
     * @inheritDoc
     */
    public function erase(): void
    {
        $this->eraseAt(0, 0);
    }

    /**
     * @inheritDoc
     */
    public function eraseAt(?int $x = null, ?int $y = null): void
    {
        $leftMargin = ($this->position["x"] ?? 0) + $x;
        $topMargin = ($this->position["y"] ?? 0) + $y;

        for ($i = 0; $i < $this->height; $i++) {
            $this->cursor->moveTo($leftMargin, $topMargin + $i);
            echo str_repeat(' ', $this->width);
        }
    }
}