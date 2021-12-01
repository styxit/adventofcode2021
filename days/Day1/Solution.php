<?php

namespace Days\Day1;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        // How many time did the depth inrease?
        $this->part1 = $this->findIncreases($this->input->lines);

        /*
         * Create windowed values.
         * For each value add the next 2 values to it.
         */
        $windowed = $this->input->lines;

        array_walk($windowed, function (&$value, $key) {
            $windowValue2 = $this->input->lines[$key + 1] ?? 0;
            $windowValue3 = $this->input->lines[$key + 2] ?? 0;

            $value += $windowValue2 + $windowValue3;
        });

        // How many time did the depth inrease when using the windowed values?
        $this->part2 = $this->findIncreases($windowed);
    }

    /**
     * Find out how many times the depth increased from one step to the next.
     *
     * @param int[]|string[] $input List of depths.
     *
     * @return int Number of times the depth increased.
     */
    private function findIncreases($input)
    {
        $depthIncreaseTimes = 0;

        $previousDepth = null;

        foreach ($input as $line) {
            $depth = (int) $line;

            if ($previousDepth !== null && $depth > $previousDepth) {
                ++$depthIncreaseTimes;
            }

            $previousDepth = $depth;
        }

        return $depthIncreaseTimes;
    }
}
