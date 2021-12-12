<?php

namespace Days\Day9;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    private $heightmap;

    private $rowLength;

    /**
     * Find the solution.
     */
    public function execute()
    {
        $this->rowLength = strlen($this->input->lines[0]);
        $this->heightmap = str_split(preg_replace('/\s+/', '', $this->input->txt));
        $lowPoints = [];

        foreach ($this->heightmap as $key => $height) {
            $neighbors = $this->getNeighbors($key);

            if ($height < min($neighbors)) {
                $lowPoints[$key] = $height;
            }
        }

        $this->part1 = array_sum($lowPoints) + count($lowPoints);

        $basinSizes = [];
        foreach (array_keys($lowPoints) as $lowPointKey) {
            $basinSizes[] = count($this->getBasin([$lowPointKey]));
        }

        // Sort descending.
        rsort($basinSizes);

        // Get the first 3 basins.
        $largestBasinSizes = array_slice($basinSizes, 0, 3);

        $this->part2 = array_product($largestBasinSizes);
    }

    private function getBasin($basinKeys)
    {
        foreach ($basinKeys as $key) {
            $neighbors = array_keys($this->getNeighbors($key));

            $neighbors = array_filter($neighbors, function ($key) use ($basinKeys) {
                return $this->heightmap[$key] < 9 && !in_array($key, $basinKeys);
            });

            $newbasinKeys = array_merge($basinKeys, $neighbors);
            if (count($basinKeys) != count($newbasinKeys)) {
                $basinKeys = $this->getBasin($newbasinKeys);
            }
        }

        return $basinKeys;
    }

    private function getNeighbors($key)
    {
        $neighbors = [
            'left' => $key - 1,
            'right' => $key + 1,
            'top' => $key - $this->rowLength,
            'bottom' => $key + $this->rowLength,
        ];

        // Remove values out of bounds.
        $neighbors = array_filter($neighbors, function ($key) {
            return $key >= 0 && $key < count($this->heightmap);
        });

        // Validate if left and right are in the same row.
        $row = $this->getRow($key);

        // Remove left if left is in a different row.
        if (isset($neighbors['left']) && $row !== $this->getRow($neighbors['left'])) {
            unset($neighbors['left']);
        }
        // Remove right if right is in a different row.
        if (isset($neighbors['right']) && $row !== $this->getRow($neighbors['right'])) {
            unset($neighbors['right']);
        }

        $neighborValues = [];
        foreach ($neighbors as $neighbor) {
            $neighborValues[$neighbor] = $this->heightmap[$neighbor];
        }

        return $neighborValues;
    }

    private function getRow($key)
    {
        return floor($key / $this->rowLength);
    }
}
