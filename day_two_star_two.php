<?php

// 7 6 4 2 1
// Determine if the reports are safe

// To be safe;
// If all numbers are increasing, or all numbers are increasing
// And if the numbers have gaps of 3 or less

// One level can be removed to make a safe record

$input = file('./public/assets/day_two_input');

echo "Reading file";

$safeRecords = 0;

foreach ($input as $key => $line) {
    $levels = explode(' ', $line);
    $allGapsAreSafe = allGapsAreSafe($levels);

    if ($allGapsAreSafe) {
        $safeRecords += 1;
        continue;
    }

    // Can one level be removed to make it safe?

    for ($i = 0; $i < count($levels); $i++) {
        $withoutLevel = $levels;
        unset($withoutLevel[$i]);

        $allGapsAreSafeWithoutLevel = allGapsAreSafe(array_values($withoutLevel));

        if ($allGapsAreSafeWithoutLevel) {
            $safeRecords += 1;
            continue 2;
        }
    }
}

echo "Safe records: " . $safeRecords;

function allGapsAreSafe(array $levels): bool
{
    $gaps = [];
    $asc = 0;
    $desc = 0;

    for ($i = 0; $i < count($levels); $i++) {
        $numOne = $levels[$i];
        $numTwo = $levels[$i + 1] ?? null;

        if ($numTwo === null) {
            continue;
        }

        // Gap is positive if ascending, or negative is descending
        $gap = $numTwo - $numOne;

        $gaps[] = $gap;

        if ($gap > 0) {
            $asc++;
        }
        if ($gap < 0) {
            $desc++;
        }
    }

    $ascendingOrder = $asc > $desc;

    $safeGaps = array_filter($gaps, function ($gap) use ($ascendingOrder) {
        if ($ascendingOrder) {
            if ($gap <= 0 || $gap > 3) {
                return false;
            }
            return true;
        }

        if ($gap >= 0 || $gap < -3) {
            return false;
        }
        return true;
    });

    return count($gaps) === count($safeGaps);
}
