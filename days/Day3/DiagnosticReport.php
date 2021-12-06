<?php

namespace Days\Day3;

use Illuminate\Support\Collection;

class DiagnosticReport
{
    /**
     * The input report.
     *
     * @var Collection The report lines.
     */
    private $report;

    public function __construct($reportLines)
    {
        $this->report = new Collection($reportLines);
    }

    public function powerConsumption()
    {
        $bitCount = strlen($this->report->first());

        $bitPartition = new Collection();

        for ($i = 0; $i < $bitCount; ++$i) {
            [$p0, $p1] = $this->report
                ->map(function ($number) use ($i) {
                    return (int) $number[$i];
                })
                ->partition(function ($bit) {
                    return 0 === $bit;
                });

            $bitPartition->push([$p0->count(), $p1->count()]);
        }

        $bitPartition->transform(function ($item) {
            return $item[0] > $item[1] ? 0 : 1;
        });

        $gammaBits = $bitPartition->join('');
        // Reverse each bit from gamma bits to get the epsilon bits
        $epsilonBits = strtr($gammaBits, [1, 0]);

        $gammaRate = bindec($gammaBits);
        $epsilonRate = bindec($epsilonBits);

        return $gammaRate * $epsilonRate;
    }

    public function oxygenRating()
    {
        return $this->extract(1);
    }

    public function co2Rating()
    {
        return $this->extract(0);
    }

    private function extract($preferredBit)
    {
        $report = $this->report;

        // The bit in each number that is being checked.
        $bit = -1;

        // Reduce the report until only one value remains in the report.
        while ($report->count() > 1) {
            // Increase bit counter to check the next bit.
            ++$bit;

            // Split the report in two groups, based on the value of the bit that is being checked.
            [$group0, $group1] = $report->partition(function ($number) use ($bit) {
                return 0 === ((int) $number[$bit]);
            });

            // Based on the preferred bit, set one of the groups as the new report values.
            $report = ($group0->count() > $group1->count()) ? ${'group'.abs($preferredBit - 1)} : ${'group'.$preferredBit};
        }

        return bindec($report->first());
    }
}
