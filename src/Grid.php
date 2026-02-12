<?php

namespace Atatusoft\Termutil;

use Atatusoft\Termutil\Interfaces\ComparableInterface;
use Atatusoft\Termutil\Interfaces\EquatableInterface;
use Atatusoft\Termutil\Interfaces\GridInterface;
use InvalidArgumentException;

/**
 * @template T * @implements GridInterface<T>
 */
class Grid implements GridInterface
{
    private const mixed INITIAL_VALUE = 0;

    protected(set) string $hash {
        get {
            return $this->hash;
        }
    }

    protected(set) int $width {
        get {
            return $this->gridWidth;
        }

        set {
            $this->gridWidth = $value;
        }
    }

    protected(set) int $height {
        get {
            return $this->gridHeight;
        }

        set {
            $this->gridHeight = $value;
        }
    }

    protected int $gridWidth = 0;
    protected int $gridHeight = 0;

    /**
     * @var T[][] $grid
     */
    protected array $grid = [];

    /**
     * Constructor for the Grid class.
     *
     * @param int $width The width of the grid.
     * @param int $height The height of the grid.
     * @param mixed $initialValue The value that will be used to fill the grid. Defaults to a space character.
     */
    public function __construct(
        int $width,
        int $height,
        protected mixed $initialValue = ' '
    )
    {
        $this->hash = uniqid(__CLASS__, true) . '-' . md5(__CLASS__);
        $this->width = $width;
        $this->height = $height;
        $this->grid = array_fill(0, $height, array_fill(0, $width, $initialValue));
    }

    /**
     * @inheritdoc
     */
    public function get(int $x, int $y): mixed
    {
        return $this->grid[$y][$x];
    }

    /**
     * @inheritdoc
     */
    public function set(int $x, int $y, mixed $value): void
    {
        $this->grid[$y][$x] = $value;
    }

    /**
     * @inheritdoc
     */
    public function fill(int $x, int $y, int $width, int $height, mixed $value): void
    {
        for ($i = $y; $i < $y + $height; $i++) {
            for ($j = $x; $j < $x + $width; $j++) {
                $this->grid[$i][$j] = $value;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function contains(mixed $value): bool
    {
        foreach ($this->grid as $row) {
            if (in_array($value, $row)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->grid;
    }

    /**
     * Clears the grid by filling it with spaces.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->grid = array_fill(0, $this->height, array_fill(0, $this->width, ' '));
    }

    public function __toString(): string
    {
        return implode("\n", array_map(fn($row) => implode('', $row), $this->grid));
    }

    public function compareTo(ComparableInterface $other): int
    {
        if (! $other instanceof Grid) {
            throw new InvalidArgumentException('The other object must be an instance of Grid.');
        }

        return match (true) {
            $this->width > $other->width, $this->height > $other->height => 1,
            $this->width < $other->width, $this->height < $other->height => -1,
            default => 0,
        };
    }

    public function greaterThan(ComparableInterface $other): bool
    {
        return $this->compareTo($other) > 0;
    }

    public function greaterThanOrEqual(ComparableInterface $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    public function lessThan(ComparableInterface $other): bool
    {
        return $this->compareTo($other) < 0;
    }

    public function lessThanOrEqual(ComparableInterface $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    public function equals(EquatableInterface $equatable): bool
    {
        if (! $equatable instanceof Grid) {
            throw new InvalidArgumentException('The equatable object must be an instance of Grid.');
        }

        return $this->compareTo($equatable) === 0;
    }

    public function notEquals(EquatableInterface $equatable): bool
    {
        return ! $this->equals($equatable);
    }

    /**
     * Validates the coordinates.
     *
     * @param int $x The x-coordinate.
     * @param int $y The y-coordinate.
     * @return void
     */
    private function validateCoordinates(int $x, int $y): void
    {
        if ($x < 0 || $x >= $this->width) {
            throw new InvalidArgumentException("The x coordinate, ($x), must be between 0 and the width, $this->width, of the grid.");
        }

        if ($y < 0 || $y >= $this->height) {
            throw new InvalidArgumentException("The y coordinate, ($y), must be between 0 and the height, $this->height, of the grid.");
        }
    }

    /**
     * Provisions space in the grid. If the space is not available, it is created.
     *
     * @param int $x The x-coordinate.
     * @param int $y The y-coordinate.
     * @return void
     */
    private function provisionSpace(int $x, int $y): void
    {
        if (! isset($this->grid[$y]) ) {
            $numberOfRowsToAdd = $y - count($this->grid) + 1;
            $this->grid = array_merge($this->grid, array_fill(0, $numberOfRowsToAdd, array_fill(0, $this->width, $this->initialValue)));
        }

        if (! isset($this->grid[$y][$x]) ) {
            $numberOfColumnsToAdd = $x - count($this->grid[$y]) + 1;
            $this->grid[$y] = array_merge($this->grid[$y], array_fill(0, $numberOfColumnsToAdd, $this->initialValue));
        }
    }
}
