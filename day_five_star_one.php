<?php
$input = file('./public/assets/day_five_input.txt');

echo "Reading file";
echo "\n";

$rules = [];
$updates = [];

// Register rules
foreach ($input as $line) {
    $isRule = count(explode('|', $line)) === 2;

    if ($isRule) {
        // Register rule
        $rule = explode('|', $line);

        foreach ($rule as $key => $number) {
            $number = trim($number);

            if (!array_key_exists($number, $rules)) {
                $mustBeBefore = [];
                $mustBeAfter = [];

                if ($key === 0) {
                    $mustBeBefore[] = trim($rule[1]);
                }

                if ($key === 1) {
                    $mustBeAfter[] = trim($rule[0]);
                }

                $rules[$number] = new PageRule($number, $mustBeBefore, $mustBeAfter);

                continue;
            }

            $existingRule = $rules[$number];
            // Rule for number already exists, update
            if ($key === 0) {
                $existingRule->mustBeBefore[] = $rule[1];
            }

            if ($key === 1) {
                $existingRule->mustBeAfter[] = $rule[0];
            }

            $rules[$number] = $existingRule;
        }

        continue;
    }

    if ($line === "\n") {
        continue;
    }

    $updates[] = explode(',', $line);
}

$total = 0;
foreach ($updates as $update) {
    $total = addCorrectPageToTotal($total, $update, $rules);
}

print_r('The total correct updates: ' . $total);
class PageRule
{
    public function __construct(
        public int $page,
        public array $mustBeBefore = [],
        public array $mustBeAfter = [],
    ) {
    }
}

function addCorrectPageToTotal(int $total, array $update, array $rules): int {
    $correct = true;

    // Check if all numbers follow the rules
    foreach ($update as $location => $page) {
        $page = trim($page);
        $hasRules = array_key_exists($page, $rules);

        if ($hasRules) {
            // Is this line per rules?
            $checks = $rules[$page];

            foreach ($checks->mustBeBefore as $before) {
                $beforeIsInUpdate = in_array($before, $update);

                if ($beforeIsInUpdate) {
                    $beforeLocation = array_search($before, $update);

                    if ($beforeLocation < $location) {
                        $correct = false;
                    }
                }
            }

            foreach ($checks->mustBeAfter as $after) {
                $afterIsInUpdate = in_array($after, $update);

                if ($afterIsInUpdate) {
                    $afterLocation = array_search($after, $update);

                    if ($afterLocation > $location) {
                        $correct = false;
                    }
                }
            }
        }
    }

    $middle = $update[floor(count($update) / 2)];

    // If yes, get the middle number
    if ($correct){
        return $total + $middle;
    }

    return $total;
}

