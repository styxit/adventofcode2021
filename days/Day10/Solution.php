<?php

namespace Days\Day10;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * The keys of this array are considered opening characters. The values are the corresponding closing characters.
     *
     * @var array
     */
    private $openCharacters = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    /**
     * The keys of this array are considered closing characters. The values are the corresponding opening characters.
     *
     * @var array
     */
    private $closingCharacters = [
        ')' => '(',
        ']' => '[',
        '}' => '{',
        '>' => '<',
    ];

    /**
     * Points awarded for corrupted characters.
     *
     * @var array
     */
    private $corruptedCharacterPoints = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    /**
     * Points awarded when this character was missing.
     *
     * @var array
     */
    private $missingCharacterPoints = [
        ')' => 1,
        ']' => 2,
        '}' => 3,
        '>' => 4,
    ];

    /**
     * Solutions to complete lines.
     *
     * @var array
     */
    private $incompleteLines = [];

    /**
     * Find the solution.
     */
    public function execute()
    {
        $corruptedCharacters = [];

        $incompleteLines = [];

        foreach ($this->input->lines as $line) {
            $lineCheck = $this->checkCompleteLine($line);

            if ($lineCheck === true) {
                $incompleteLines[] = $line;
            } elseif (is_string($lineCheck)) {
                $corruptedCharacters[] = $lineCheck;
            }
        }

        //Convert corrupted characters to points.
        array_walk($corruptedCharacters, function (&$char) {
            $char = $this->corruptedCharacterPoints[$char];
        });

        $this->part1 = array_sum($corruptedCharacters);

        $linePoints = collect($this->incompleteLines)->map(function ($missingCharacters) {
            return collect($missingCharacters)->map(function ($char) {
                return $this->missingCharacterPoints[$char];
            })->reduce(function ($carry, $score) {
                return ($carry * 5) + $score;
            }, 0);
        })->sort()->values();

        // Find the key of the value that sits in the middle.
        $key = floor($linePoints->count() / 2);

        // The solution is the score that sits in the middle.
        $this->part2 = $linePoints[$key];
    }

    /**
     * Check if a line is complete or currupt.
     *
     * If the line is complete (bool) true is returned. If the line is incomplete (bool) false is returned.
     * Returns a string if the lines was currupt. The character that causes the line to be corrupt is returned.
     *
     * @param string $line The line to check.
     *
     * @return bool|string Boolean to indicate if line is complete or not. If the line is corrupt the character that caused corruption is returned.
     */
    private function checkCompleteLine($line)
    {
        $characters = str_split($line);

        $chunks = [];

        foreach ($characters as $char) {
            if ($this->isOpenCharacter($char)) {
                $chunks[] = $char;

                continue;
            }
            if ($this->isClosingCharacter($char, end($chunks))) {
                // Remove the last item of the chunks array, because a closing match was found.
                array_pop($chunks);

                continue;
            }

            return $char;
        }

        // If there are no more chunks the line is complete.

        if (count($chunks)) {
            $this->fixLine($chunks);

            return false;
        }

        return true;
    }

    /**
     * Find the solution to fix the line.
     *
     * @param array $chunks The individual letters in the line.
     */
    private function fixLine($chunks)
    {
        // Reverse the chunks to start at the end.
        $chunks = array_reverse($chunks);

        $missingCharacters = [];

        // Loop reversed chunks/
        foreach ($chunks as $char) {
            // If $this is an opening character, add the closing matching character to the missing characters.
            if ($this->isOpenCharacter($char)) {
                $missingCharacters[] = $this->openCharacters[$char];
            } else {
                break;
            }
        }

        $this->incompleteLines[] = $missingCharacters;
    }

    /**
     * Is the provided character an opening character?
     *
     * @param string $character The character to check.
     *
     * @return bool True when this is an opening character, false otherwise.
     */
    private function isOpenCharacter($character)
    {
        return isset($this->openCharacters[$character]);
    }

    /**
     * Is the provided character a closing character for the matching opening character?
     *
     * @param string $character The character to check.
     * @param string $for       The character to match as opening character.
     *
     * @return bool True when this is a closing character, false otherwise.
     */
    private function isClosingCharacter($character, $for)
    {
        return isset($this->closingCharacters[$character]) && $this->closingCharacters[$character] == $for;
    }
}
