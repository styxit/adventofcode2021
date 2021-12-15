<?php

namespace Days\Day11;

use Illuminate\Support\Collection;
use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Width of the grid, used for calculations.
     *
     * @var int
     */
    private $gridWidth;

    /**
     * The grid with Octopus.
     *
     * @var Collection The grid.
     */
    private $grid;

    /**
     * Steps currently executed.
     *
     * @var int
     */
    private $steps = 0;

    /**
     * Step in which a mega flash was found.
     *
     * @var int
     */
    private $megaFlashStep = 0;

    /**
     * Find the solution.
     */
    public function execute()
    {
        $this->gridWidth = strlen($this->input->lines[0]);

        // Initialize a grid of octopus.
        $this->grid = collect(str_split(preg_replace('/\s+/', '', $this->input->txt)));
        $this->grid->transform(function ($energyLevel) {
            return new Octopus($energyLevel);
        });

        // Execute 100 steps.
        $stepsToExecute = 100;
        while ($this->steps < $stepsToExecute) {
            $this->step();
        }
        // The total flash count after 100 steps is the solution for part 1.
        $this->part1 = $this->grid->map->getFlashCount()->sum();

        // Keep stepping until a mega flash was found.
        while (!$this->megaFlashStep) {
            $this->step();
        }

        $this->part2 = $this->megaFlashStep;
    }

    /**
     * Execute a step.
     * Add energy to all octopus,
     * Flash octopus with more than 9 energy.
     * - If other octopus now reached 9 energy flash them aswell (repeat).
     *
     * Reset octopus that flashed to energy level 0.
     *
     * Also keeps track of mega flashes, meaning all octopus flashed this step.
     */
    private function step()
    {
        // Increase the step counter.
        ++$this->steps;
        // Add 1 energy to every octopus.
        $this->grid->each->addEnergy();

        // As log as there are octopus that should flash, flash them.
        while ($this->grid->filter->shouldFlash()->isNotEmpty()) {
            $this->grid->filter->shouldFlash()->each(function (Octopus $octopus, $key) {
                // Make the octopus flash.
                $octopus->flash();

                // Find all neighbors and add some energy, caused by the flash,
                $this->getNeighbors($key)->each->addEnergy();
            });
        }

        // Check if ALL octopuses flashed this step.
        if ($this->grid->count() === $this->grid->filter->hasFlashed()->count()) {
            $this->megaFlashStep = $this->steps;
        }

        // Reset the octopus that flashed.
        $this->grid->filter->hasFlashed()->each->reset();
    }

    /**
     * Get collection of neighbor actopus.
     *
     * @param int $key The key on the grid of the octopus to find neighbors for.
     *
     * @return Collection Collection of octopus.
     */
    private function getNeighbors($key)
    {
        // At most there can be 8 neighbors.
        $neighbors = [
            // 3 in the top line.
            'tl' => $key - $this->gridWidth - 1,
            'tc' => $key - $this->gridWidth,
            'tr' => $key - $this->gridWidth + 1,

            // 2, next to it.
            'ml' => $key - 1,
            'mr' => $key + 1,

            // 3 in the bottom line.
            'bl' => $key + $this->gridWidth - 1,
            'bc' => $key + $this->gridWidth,
            'br' => $key + $this->gridWidth + 1,
        ];

        // Determine the row the current key is in.
        $row = $this->getRow($key);

        // Remove neighbors on the left if left is in a different row.
        if (isset($neighbors['ml']) && $row !== $this->getRow($neighbors['ml'])) {
            unset($neighbors['tl'], $neighbors['ml'], $neighbors['bl']);
        }
        // Remove neighbors on the right if right is in a different row.
        if (isset($neighbors['mr']) && $row !== $this->getRow($neighbors['mr'])) {
            unset($neighbors['tr'], $neighbors['mr'], $neighbors['br']);
        }

        // Remove keys that are out of bounds.
        $neighbors = array_filter($neighbors, function ($key) {
            return $key >= 0 && $key < $this->grid->count();
        });

        // Return collection of neighbor octopus.
        return collect($neighbors)->mapWithKeys(function ($key) {
            return [$key => $this->grid->get($key)];
        });
    }

    /**
     * Determine in which row a key is on the grid.
     *
     * @param int $key The key to check.
     *
     * @return int The row on the grid.
     */
    private function getRow($key)
    {
        return floor($key / $this->gridWidth);
    }
}
