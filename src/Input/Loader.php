<?php

namespace Styxit\Input;

class Loader
{
    /**
     * @var string The plain text input.
     */
    public $txt = '';

    /**
     * @var string[] The input separated by line.
     */
    public $lines = [];

    /**
     * Loader constructor.
     *
     * @param string $input The full path to the input file to load.
     */
    public function __construct($input)
    {
        $this->txt = trim(file_get_contents($input));
        $this->lines = explode(PHP_EOL, $this->txt);
    }
}
