<?php

namespace Tests\Days;

use PHPUnit\Framework\TestCase;

abstract class AbstractDayTest extends TestCase
{   
    /**
     * Classname of the solution to test.
     * Should be overwritten by the test class.
     */
    protected static $class;

    /**
     * Instance of the day solution.
     */
    private static $solution;

    /**
     * Load the test class and exectute it to get solution for day 1 and 2.
     */
    public static function setUpBeforeClass(): void
    {
        /**
         * create an instance of the solution.
         * Use late satic binding to load the string as defined in the test: https://www.php.net/lsb
         */
        self::$solution = new static::$class();

         // Execute day solution.
         self::$solution->execute();
    }

    /**
     * Assert solution for day 1.
     */
    public function testPart1()
    {
        $this->assertSame($this->solutionPart1, self::$solution->part1);
    }

    /**
     * Assert solution for day 2.
     */
    public function testPart2()
    {
        $this->assertSame($this->solutionPart2, self::$solution->part2);
    }
}