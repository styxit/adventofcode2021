<?php

namespace Days\Day2;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        // Create a new submarine and define how it moves.
        $submarineOne = new Submarine([
            'forward' => 'moveForward',
            'up' => 'moveUp',
            'down' => 'moveDown',
        ]);

        // Let the submarine execute each step.
        foreach ($this->input->lines as $line) {
            list($movement, $amount) = explode(' ', $line);
            $submarineOne->step($movement, $amount);
        }

        // Solution to part one.
        $this->part1 = $submarineOne->getHorizontal() * $submarineOne->getDepth();

        // Create a new submarine and define how it moves.
        $submarineTwo = new Submarine([
            'forward' => 'moveForward',
            'up' => 'aimUp',
            'down' => 'aimDown',
        ]);

        // Let the submarine execute each step.
        foreach ($this->input->lines as $line) {
            list($movement, $amount) = explode(' ', $line);
            $submarineTwo->step($movement, $amount);
        }

        // Solution to part two.
        $this->part2 = $submarineTwo->getHorizontal() * $submarineTwo->getDepth();
    }
}
