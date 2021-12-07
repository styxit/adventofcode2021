<?php

namespace Days\Day4;

use Illuminate\Support\Collection;

class BingoBoard
{
    private $field;

    /**
     * Combination of 5 keys that can result in bingo.
     *
     * @param [type] $numbers
     */
    private $bingoKeys = [
        // Rows.
        [0, 1, 2, 3, 4],
        [5, 6, 7, 8, 9],
        [10, 11, 12, 13, 14],
        [15, 16, 17, 18, 19],
        [20, 21, 22, 23, 24],
        // Columns.
        [0, 5, 10, 15, 20],
        [1, 6, 11, 16, 21],
        [2, 7, 12, 17, 22],
        [3, 8, 13, 18, 23],
        [4, 9, 14, 19, 24],
    ];

    /**
     * Create new bingo board.
     *
     * @param int[] $numbers The numbers that go on the board.
     */
    public function __construct($numbers)
    {
        $this->field = new Collection($numbers);
    }

    public function markNumber($drawNumber)
    {
        // Replace numbers in the field with false.
        $this->field->filter(function ($number) use ($drawNumber) {
            return $drawNumber === $number;
        })->keys()->each(function ($foundKey) {
            $this->field[$foundKey] = false;
        });
    }

    public function hasBingo()
    {
        foreach ($this->bingoKeys as $bingoKeys) {
            // Get specific values that could result in bingo, from the field.
            $values = array_map(function ($bingoKey) {
                return $this->field->get($bingoKey);
            }, $bingoKeys);

            // Remove values that are already marked.
            $unmarkedValues = array_filter($values, function ($v) {
                return $v !== false;
            });

            // If there are no unmarked values that means this results in a bingo.
            if (count($unmarkedValues) === 0) {
                return true;
            }
        }

        return false;
    }

    public function unmarkedNumerSum()
    {
        return $this->field->filter()->sum();
    }
}
