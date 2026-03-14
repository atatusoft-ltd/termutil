<?php

namespace Atatusoft\Termutil\IO\Console;

use Atatusoft\Termutil\Grid;
use Atatusoft\Termutil\IO\Enumerations\Color;
use Atatusoft\Termutil\IO\Enumerations\MouseTrackingMode;
use Exception;

/**
 * Console is a static class that provides console functionality.
 */
class Console
{
    /**
     * @var int $width The width of the console
     */
    protected static int $width = DEFAULT_TERMINAL_WIDTH;
    /**
     * @var int $height The height of the console
     */
    protected static int $height = DEFAULT_TERMINAL_HEIGHT;
    /**
     * @var Grid<string> $buffer The buffer.
     */
    private static Grid $buffer;
    /**
     * @var string $previousTerminalSettings The previous terminal settings.
     */
    private static string $previousTerminalSettings = "";

    /**
     * Console constructor.
     */
    private function __construct()
    {
        // Prevent instantiation.
    }

    /**
     * Initializes the console.
     *
     * @param array<string, mixed> $options
     * @return void
     */
    public static function init(
        array $options = [
            "width" => DEFAULT_TERMINAL_WIDTH,
            "height" => DEFAULT_TERMINAL_HEIGHT,
        ],
    ): void {
        self::clear();
        Console::cursor()->disableBlinking();
        self::$width = $options["width"] ?? DEFAULT_TERMINAL_WIDTH;
        self::$height = $options["height"] ?? DEFAULT_TERMINAL_HEIGHT;
    }

    /**
     * Clears the console.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$buffer = self::getEmptyBuffer();
        if (PHP_OS_FAMILY === "Windows") {
            system("cls");
        } else {
            system("clear");
        }
    }

    /**
     * Returns an empty buffer.
     *
     * @return Grid<string> The empty buffer.
     */
    private static function getEmptyBuffer(): Grid
    {
        return new Grid(DEFAULT_TERMINAL_HEIGHT, DEFAULT_TERMINAL_WIDTH, " ");
    }

    /**
     * Returns the cursor.
     *
     * @return Cursor The cursor.
     */
    public static function cursor(): Cursor
    {
        return Cursor::getInstance();
    }

    /**
     * Resets the console.
     *
     * @return void
     */
    public static function reset(): void
    {
        if (false === system("reset")) {
            echo "System reset failed";
            echo "\033c";
            self::cursor()->enableBlinking();
        }
    }

    /* Scrolling */

    /**
     * Enables the line wrap.
     *
     * @return void
     */
    public static function enableLineWrap(): void
    {
        echo "\033[7h";
    }

    /**
     * Disables the line wrap.
     *
     * @return void
     */
    public static function disableLineWrap(): void
    {
        echo "\033[7l";
    }

    /**
     * Enables scrolling.
     *
     * @param int|null $start The line to start scrolling.
     * @param int|null $end The line to end scrolling.
     * @return void
     */
    public static function enableScrolling(
        ?int $start = null,
        ?int $end = null,
    ): void {
        if ($start !== null && $end !== null) {
            echo "\033[$start;{$end}r";
        } elseif ($start !== null) {
            echo "\033[{$start}r";
        } elseif ($end !== null) {
            echo "\033[;{$end}r";
        } else {
            echo "\033[r";
        }
    }

    /**
     * Disables scrolling.
     *
     * @return void
     */
    public static function disableScrolling(): void
    {
        echo "\033[?7l";
    }

    /**
     * Sets the terminal name.
     *
     * @param string $name The name of the terminal.
     * @return void
     */
    public static function setName(string $name): void
    {
        echo "\033]0;$name\007";
    }

    /**
     * Sets the terminal size.
     *
     * @param int $width The width of the terminal.
     * @param int $height The height of the terminal.
     * @return void
     */
    public static function setSize(int $width, int $height): void
    {
        echo "\033[8;$height;{$width}t";
    }

    /**
     * Returns the terminal size.
     *
     * @return array The terminal size.
     * @throws Exception If the terminal size cannot be retrieved.
     */
    public static function getSize(): array
    {
        $width =
            (int) trim(shell_exec("tput cols")) ?:
            throw new Exception("Failed to get terminal width.");
        $height =
            (int) trim(shell_exec("tput lines")) ?:
            throw new Exception("Failed to get terminal height.");

        return ["x" => 1, "y" => 1, "width" => $width, "height" => $height];
    }

    /**
     * Saves the terminal settings.
     *
     * @return void
     */
    public static function saveSettings(): void
    {
        self::$previousTerminalSettings = shell_exec("stty -g") ?? "";
    }

    /**
     * Restores the terminal settings.
     *
     * @return void
     */
    public static function restoreSettings(): void
    {
        shell_exec("stty " . self::$previousTerminalSettings);
        Console::cursor()->enableBlinking();
    }

    /**
     * Writes a single character to the console at the specified position.
     *
     * @param string $character The character to write.
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function writeChar(string $character, int $x, int $y): void
    {
        $cursor = self::cursor();

        $x = max(1, $x);
        $y = max(1, $y);

        self::$buffer->set($x, $y, substr($character, 0, 1));
        $cursor->moveTo($x, $y);
        echo self::$buffer->toArray()[$y][$x];

        $cursor->moveTo($x + 1, $y);
    }

    /**
     * Writes a message to the console at the specified position.
     *
     * @param string $message The character to write.
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function write(string $message, int $x, int $y): void
    {
        $cursor = self::cursor();
        $messageLength = strlen($message);

        $x = max(1, $x);
        $y = max(1, $y);

        for ($index = 0; $index < $messageLength; ++$index) {
            self::$buffer->set($x + $index, $y, $message[$index]);
            $cursor->moveTo($x + $index, $y);
            echo self::$buffer->toArray()[$y][$x + $index];
        }

        $cursor->moveTo($x + $messageLength, $y);
    }

    /**
     * Writes text to the console at the specified position.
     *
     * @param array<string> $linesOfText The lines of text to write.
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function writeLines(array $linesOfText, int $x, int $y): void
    {
        $cursor = self::cursor();

        $x = max(1, $x);
        $y = max(1, $y);

        foreach ($linesOfText as $rowIndex => $text) {
            self::writeLine($text, $x, $y + $rowIndex);
        }

        $cursor->moveTo(0, $y);
    }

    /**
     * Writes text to the console at the specified position.
     *
     * @param string $message The text to write.
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function writeLine(string $message, int $x, int $y): void
    {
        $cursor = self::cursor();
        $x = max(1, $x);
        $y = max(1, $y);

        $messageLength = strlen($message);
        $columnStart = $x;
        $columnEnd = $x + $messageLength;

        for ($i = $columnStart; $i < $columnEnd; $i++) {
            self::$buffer->set($i, $y, $message[$i - $columnStart]);
        }

        $cursor->moveTo(0, $y);
        echo implode(self::$buffer->toArray()[$y]);
        $cursor->moveTo(0, $y + 1);
    }

    /**
     * Writes text to the console at the specified position in the specified color.
     *
     * @param Color $color The color.
     * @param string $message The text to write.
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function writeInColor(
        Color $color,
        string $message,
        int $x,
        int $y,
    ): void {
        echo $color->value;
        self::writeLine($message, $x, $y);
        echo Color::RESET->value;
    }

    /**
     * Erases the character at the specified position.
     *
     * @param int $x The x position.
     * @param int $y The y position.
     * @return void
     */
    public static function erase(int $x, int $y): void
    {
        self::writeLine(" ", $x, $y);
    }

    /**
     * Returns the buffer.
     *
     * @return Grid The buffer.
     */
    public static function getBuffer(): Grid
    {
        return self::$buffer;
    }

    /**
     * Returns the character at the specified position.
     *
     * @param int $x The x position.
     * @param int $y The y position.
     * @return string The character at the specified position.
     */
    public static function charAt(int $x, int $y): string
    {
        if (
            $x < 0 ||
            $x > DEFAULT_TERMINAL_WIDTH ||
            $y < 1 ||
            $y > DEFAULT_TERMINAL_HEIGHT
        ) {
            return "";
        }

        $char = substr(self::$buffer[$y], $x, 1);
        return ord($char) === 0 ? " " : $char;
    }

    /**
     * @param MouseTrackingMode $mode
     * @param bool $withSGRExtendedMode
     * @return void
     */
    public static function enableMouseReporting(
        MouseTrackingMode $mode = MouseTrackingMode::ALL_MOTION_TRACKING,
        bool $withSGRExtendedMode = true
    ): void
    {
        echo $mode->getSequence(withSGRExtendedMode: $withSGRExtendedMode);
    }

    /**
     * @return void
     */
    public static function disableMouseReporting(): void
    {
        echo "\033[?1000;1002;1003;1006l";
    }
}
