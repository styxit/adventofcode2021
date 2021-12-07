<?php

namespace Days\Day4;

use Illuminate\Support\Collection;
use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        // Load bingo boards from the input.
        $boards = $this->loadBingoBoards();
        // Create array of numbers to draw, first line from the input.
        $drawNumbers = explode(',', $this->input->lines[0]);

        // Keep track of which nummer is currently drawn.
        $currentNumber = null;

        while ($boards->isNotEmpty()) {
            // Advance the draw number pointer, except on the first loop.
            if (!is_null($currentNumber)) {
                next($drawNumbers);
            }

            // Get new number to mark on the boards.
            $currentNumber = current($drawNumbers);

            // Mark the number on each board.
            $boards->each->markNumber($currentNumber);

            /*
             * Split the boards in two groups, one with bingo and the ones without.
             * The ones without bingo will be used in the next loop.
             */
            [$bingoBoards, $boards] = $boards->partition->hasBingo();

            // If this is the first board having a bingo, this is the winning board.
            if (empty($this->part1) && $bingoBoards->isNotEmpty()) {
                $this->part1 = $bingoBoards->first()->unmarkedNumerSum() * $currentNumber;
            }

            // If this was the last board having a bingo, this is the losing board.
            if ($boards->isEmpty() && $bingoBoards->isNotEmpty()) {
                $this->part2 = $bingoBoards->first()->unmarkedNumerSum() * $currentNumber;
            }
        }
    }

    /**
     * Parse input and turn it into bingo boards.
     *
     * @return Collection A collection of BingoBoards.
     */
    private function loadBingoBoards()
    {
        $boardNumbers = new Collection(explode("\n\n", $this->input->txt));
        // Remove the first line since this are the numbers being drawn.
        $boardNumbers->forget(0);

        return $boardNumbers->map(function ($numbers) {
            // Remove newlines and extra spaces.
            $numbers = trim(preg_replace('/\s+/', ' ', $numbers));

            return new BingoBoard(explode(' ', $numbers));
        });
    }
}
