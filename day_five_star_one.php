<?php
$input = file('./public/assets/day_five_input.txt');

echo "Reading file";
echo "\n";

// Register rules
$rules = [];

// foreach line as input
foreach ($input as $line) {
    $isRule = count(explode('|', $line)) === 2;

    if ($isRule) {
        // Register rule
        $rule = explode('|', $line);

        foreach ($rule as $key => $number) {
            $mustBeBefore = [];
            $mustBeAfter = [];
            $existingRule = $rules[$number];

            if (!isset($existingRule)) {
                if ($key === 0) {
                    $mustBeBefore[] = $rule[1];
                }

                if ($key === 1) {
                    $mustBeAfter[] = $rule[0];
                }

                $rules[$number] = new PageRule($number, $mustBeBefore, $mustBeAfter);

                continue;
            }

            // Rule for number already exists, update
            if ($key === 0) {
                $existingRule->addMustBeBefore($rule[1]);
            }

            if ($key === 1) {
                $existingRule->addMustBeAfter($rule[0]);
            }

            $rules[$number] = $existingRule;
        }

    }

}
//print_r($rules);

class PageRule
{
    public function __construct(
        public int $page,
        public array $mustBeBefore = [],
        public array $mustBeAfter = [],
    ) {
    }

    public function addMustBeBefore($number)
    {
        $this->mustBeBefore[] = $number;
    }

    public function addMustBeAfter($number)
    {
        $this->mustBeAfter[] = $number;
    }
}

