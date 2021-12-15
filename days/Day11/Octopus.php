<?php

namespace Days\Day11;

class Octopus
{
    /**
     * The current energy level of the octopus.
     *
     * @var int
     */
    private $energyLevel = 0;

    /**
     * Keep track of total times a flash happened.
     *
     * @var int
     */
    private $flashCount = 0;

    /**
     * Did a flash happen? Can be reset.
     *
     * @var bool
     */
    private $flashed = false;

    /**
     * Create octopus.
     *
     * @param int $energyLevel Initial energy level.
     */
    public function __construct($energyLevel = 0)
    {
        $this->energyLevel = $energyLevel;
    }

    /**
     * Increate the energy leverl by 1.
     */
    public function addEnergy()
    {
        ++$this->energyLevel;
    }

    /**
     * Get the current energy level.
     *
     * @return int Current energy level.
     */
    public function getEnergy()
    {
        return $this->energyLevel;
    }

    /**
     * Mark as flashed.
     */
    public function flash()
    {
        $this->flashed = true;
        ++$this->flashCount;
    }

    /**
     * Should this octopus flash?
     *
     * Only when energy level is over 9 and it has not yet flashed.
     *
     * @return bool
     */
    public function shouldFlash()
    {
        return $this->energyLevel > 9 && !$this->hasFlashed();
    }

    /**
     * Did a flash already happen?
     *
     * @return bool
     */
    public function hasFlashed()
    {
        return $this->flashed === true;
    }

    /**
     * Get total flash count.
     *
     * @return int Total times flashed.
     */
    public function getFlashCount()
    {
        return $this->flashCount;
    }

    /**
     * Reset flash state and energy level.
     */
    public function reset()
    {
        $this->flashed = false;
        $this->energyLevel = 0;
    }
}
