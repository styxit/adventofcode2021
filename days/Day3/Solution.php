<?php

namespace Days\Day3;

use Styxit\AbstractSolution;

class Solution extends AbstractSolution
{
    /**
     * Find the solution.
     */
    public function execute()
    {
        $diagnosticReport = new DiagnosticReport($this->input->lines);

        $this->part1 = $diagnosticReport->powerConsumption();

        $oxygen = $diagnosticReport->oxygenRating();
        $co2 = $diagnosticReport->co2Rating();

        $this->part2 = $oxygen * $co2;
    }
}
