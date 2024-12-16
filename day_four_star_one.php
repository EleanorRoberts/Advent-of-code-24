<?php
$input = file('./public/assets/day_four_input.txt');

echo "Reading file";
echo "\n";

// XMAS can appear in any direction; horizontally, vertically or diagonally
// Determine the amount of times XMAS appears in the word search
// ..X...
// .SAMX.
// .A..A.
// XMAS.S
// .X....

$map = [
    'north' => [0, -1],
    'northeast' => [1, -1],
    'east' => [1, 0],
    'southeast' => [1, 1],
    'south' => [0, 1],
    'southwest' => [-1, 1],
    'west' => [-1, 0],
    'northwest' => [-1, -1],
];

// Find X, then determine if the word can be found in any direction

$letterMap = [];
$xLocations = [];
$xmasCount = 0;

foreach ($input as $y => $line) {
    $letters = str_split($line);
    $stripNewLines = array_filter($letters, function ($l) {
        return $l !== "\n";
    });
    $letterMap[] = $letters;

    foreach ($letters as $x => $letter) {
        if ($letter === 'X') {
            $xLocations[] = [ 'x' => $x, 'y' => $y ];
        }
    }
}

foreach ($xLocations as $coordinates) {
    foreach ($map as $direction) {
        $firstCoordinates = getNextCoordinates($coordinates, $direction);
        $secondLetterInDirection = getNextLetter($firstCoordinates['x'], $firstCoordinates['y'], $letterMap);

        if ($secondLetterInDirection !== 'M') {
            continue;
        }

        $secondCoordinates = getNextCoordinates($firstCoordinates, $direction);
        $thirdLetterInDirection = getNextLetter($secondCoordinates['x'], $secondCoordinates['y'], $letterMap);

        if ($thirdLetterInDirection !== 'A') {
            continue;
        }

        $thirdCoordinates = getNextCoordinates($secondCoordinates, $direction);
        $thirdLetterInDirection = getNextLetter($thirdCoordinates['x'], $thirdCoordinates['y'], $letterMap);

        if ($thirdLetterInDirection === 'S') {
            $xmasCount++;
        }
    }
}

echo $xmasCount;

function getNextCoordinates(array $coordinates, $direction): array
{
    $newX = $coordinates['x'] + $direction[0];
    $newY = $coordinates['y'] + $direction[1];

    return [ 'x' => $newX, 'y' => $newY ];
}

function getNextLetter($newX, $newY, $letterMap)
{
//    if (
//        $newX < 0
//        || $newX >= 140
//        || $newY < 0
//        || $newY >= 140
//    ) {
//    }
    if (!isset($letterMap[$newY][$newX])) {
        return null;
    }

    return $letterMap[$newY][$newX];
}

