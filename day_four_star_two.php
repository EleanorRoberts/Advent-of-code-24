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
//    [
        'northeast' => [1, -1],
        'southwest' => [-1, 1],
//    ],
//    [
        'southeast' => [1, 1],
        'northwest' => [-1, -1],
//    ],
//    'north' => [0, -1],
//    'east' => [1, 0],
//    'south' => [0, 1],
//    'west' => [-1, 0],
];

// Find X, then determine if the word can be found in any direction

$letterMap = [];
$aLocations = [];
$xmasCount = 0;

foreach ($input as $y => $line) {
    $letters = str_split($line);
    $stripNewLines = array_filter($letters, function ($l) {
        return $l !== "\n";
    });
    $letterMap[] = $letters;

    foreach ($letters as $x => $letter) {
        if ($letter === 'A') {
            $aLocations[] = [ 'x' => $x, 'y' => $y ];
        }
    }
}

foreach ($aLocations as $coordinates) {
    $crossOne = [];
    $crossTwo = [];

    $a = getNextCoordinates($coordinates, $map['northeast']);
    $crossOne[] = getNextLetter($a['x'], $a['y'], $letterMap);

    $b = getNextCoordinates($coordinates, $map['southwest']);
    $crossOne[] = getNextLetter($b['x'], $b['y'], $letterMap);

    if (!in_array('M', $crossOne) || !in_array('S', $crossOne)) {
        continue;
    }

    $c = getNextCoordinates($coordinates, $map['northwest']);
    $crossTwo[] = getNextLetter($c['x'], $c['y'], $letterMap);

    $d = getNextCoordinates($coordinates, $map['southeast']);
    $crossTwo[] = getNextLetter($d['x'], $d['y'], $letterMap);

    if (in_array('M', $crossTwo) && in_array('S', $crossTwo)) {
        $xmasCount++;
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
    if (!isset($letterMap[$newY][$newX])) {
        return null;
    }

    return $letterMap[$newY][$newX];
}

