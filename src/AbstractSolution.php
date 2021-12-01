<?php

namespace Styxit;

use Styxit\Input\Loader;

abstract class AbstractSolution
{
    /**
     * @var mixed The solution to part 1 of the day.
     */
    public $part1 = 0;

    /**
     * @var mixed The solution to part 2 of the day.
     */
    public $part2 = 0;

    /**
     * @var \Styxit\Input\Loader The input to pass to the solution.
     */
    public $input;
    /**
     * @var string Define where all the inputs are located.
     */
    private $inputRoot = __DIR__.'/../inputs/';

    /**
     * AbstractSolution constructor.
     */
    public function __construct()
    {
        $inputPath = $this->getInputFilePath();

        $this->input = new Loader($inputPath);
    }

    /**
     * Get the input file based on the current Solution instance.
     *
     * @return string The full path where the input file should be located.
     */
    private function getInputFilePath()
    {
        // Explode the solutions namespace.
        $namespaceSections = explode('\\', get_class($this));

        // Extract the day from the solution namespace.
        $day = strtolower($namespaceSections[1]);

        // Construct the full path to the input file.
        return $this->inputRoot.$day.'.txt';
    }
}
