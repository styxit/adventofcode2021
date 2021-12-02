<?php

namespace Days\Day2;

class Submarine
{
    private int $depth = 0;
    private int $horizontal = 0;
    private int $aim = 0;

    /**
     * Define which movement executes which action on the submarine.
     *
     * The key defines the provided movement in the step() method,
     * the value defines which method is executed o the submarine.
     *
     * [
     *     'forward' => 'moveForward',
     * ]
     */
    private array $methodMapping = [];

    /**
     * Create a new submarine.
     *
     * @param array $methodMapping
     */
    public function __construct($methodMapping)
    {
        $this->methodMapping = $methodMapping;
    }

    /**
     * Execute a step.
     * Based on the method mapping call the right action o the submarie.
     *
     * @param [type] $movement
     * @param [type] $amount
     */
    public function step($movement, $amount)
    {
        // Get method name based on mapping.
        $method = $this->methodMapping[$movement];

        // Execute the method.
        $this->{$method}($amount);
    }

    public function getDepth()
    {
        return $this->depth;
    }

    public function getHorizontal()
    {
        return $this->horizontal;
    }

    private function moveForward($amount)
    {
        $this->horizontal += $amount;
        $this->depth += $this->aim * $amount;
    }

    private function moveUp($amount)
    {
        $this->depth -= $amount;
    }

    private function moveDown($amount)
    {
        $this->depth += $amount;
    }

    private function aimUp($amount)
    {
        $this->aim -= $amount;
    }

    private function aimDown($amount)
    {
        $this->aim += $amount;
    }
}
