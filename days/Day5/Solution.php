<?php

namespace Days\Day5;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    private $grid = [];

    /**
     * Find the solution.
     */
    public function execute()
    {
        // Loop the input lines.
        foreach ($this->input->lines as $inputLine) {
            // Fill horizontal and vertical lines on the grid.
            $this->fillGrid($inputLine);
        }
        $this->part1 = $this->part2 = $this->getOverlapCount();

        // Loop the input lines.
        foreach ($this->input->lines as $inputLine) {
            // Add diagonal lines to the grid.
            $this->fillGrid($inputLine, true);
        }
        $this->part2 = $this->getOverlapCount();
    }

    /**
     * Count the times there is a value of 2 or more in the grid.
     *
     * @return int The count.
     */
    private function getOverlapCount(): int
    {
        // Get one big array of values.
        $allValues = array_reduce(
            $this->grid,
            fn ($carry, $item) => array_merge($carry, $item),
            []
        );

        // Only keep values 2 or higher.
        $highValues = array_filter(
            $allValues,
            fn ($value) => $value >= 2
        );

        return count($highValues);
    }

    private function fillGrid($line, $fillDiagonal = false)
    {
        $line = str_replace(' -> ', ',', $line);
        [$x1, $y1, $x2, $y2] = explode(',', $line);

        if ($x1 === $x2 && !$fillDiagonal) {
            $this->fillVertical($x1, $y1, $x2, $y2);
        } elseif ($y1 === $y2 && !$fillDiagonal) {
            $this->fillHorizontal($x1, $y1, $x2, $y2);
        }
        // Fill diagonal lines when needed.
        elseif ($x1 !== $x2 && $y1 !== $y2 && $fillDiagonal) {
            $this->fillDiagonal($x1, $y1, $x2, $y2);
        }
    }

    private function fillDiagonal($x1, $y1, $x2, $y2)
    {
        $yCoordinates = range($y1, $y2, $y1 < $y2 ? 1 : -1);
        $xCoordinates = range($x1, $x2, $x1 < $x2 ? 1 : -1);

        for ($i = 0; $i < count($yCoordinates); ++$i) {
            $this->incrementPoint($xCoordinates[$i], $yCoordinates[$i]);
        }
    }

    private function fillVertical($x1, $y1, $x2, $y2)
    {
        $yCoordinates = range($y1, $y2, $y1 < $y2 ? 1 : -1);

        foreach ($yCoordinates as $y) {
            $this->incrementPoint($x1, $y);
        }
    }

    private function fillHorizontal($x1, $y1, $x2, $y2)
    {
        $xCoordinates = range($x1, $x2, $x1 < $x2 ? 1 : -1);

        foreach ($xCoordinates as $x) {
            $this->incrementPoint($x, $y1);
        }
    }

    private function incrementPoint($x, $y)
    {
        if (isset($this->grid[$y][$x])) {
            ++$this->grid[$y][$x];
        } else {
            $this->grid[$y][$x] = 1;
        }
    }
}
