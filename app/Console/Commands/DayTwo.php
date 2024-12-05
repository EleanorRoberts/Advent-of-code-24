<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DayTwo extends Command
{
    private string $inputFile = '/assets/day_two_input';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day:two';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the code for day one of the advent calendar';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = file(public_path($this->inputFile));

//        if ($this->confirm('Run the second star command?', true)) {
//            $total = $this->secondStar($input);
//        } else {
            $total = $this->firstStar($input);
//        }

        $this->info('There are ' . $total . ' safe reports');

        return Command::SUCCESS;
    }

    private function firstStar($input)
    {
        $safeRecords = 0;

        foreach ($input as $key => $line) {
            $levels = explode(' ', $line);
            $gaps = new Collection();

            for ($i = 0; $i < count($levels); $i++) {
                $numOne = $levels[$i];
                $numTwo = $levels[$i + 1] ?? null;

                if ($numTwo === null) {
                    continue;
                }

                $gaps->add($numTwo - $numOne);
            }
            $ascendingOrder = $gaps->first() > 0;

            $safeGaps = $gaps->filter(function($gap) use ($ascendingOrder) {
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

            if (count($gaps) === $safeGaps->count()) {
                $safeRecords += 1;
            }
        }

        return $safeRecords;
    }

    private function secondStar($input)
    {
        $safeRecords = 0;

        foreach ($input as $key => $line) {
            $levels = explode(' ', $line);
            $gaps = new Collection();

            for ($i = 0; $i < count($levels); $i++) {
                $numOne = $levels[$i];
                $numTwo = $levels[$i + 1] ?? null;

                if ($numTwo === null) {
                    continue;
                }

                $gaps->add($numTwo - $numOne);
            }

            $ascendingOrder = $gaps->first() > 0;

//            $safeRecords = $gaps->reduce(function ($carry, $gap) use ($ascendingOrder) {
//                if ($ascendingOrder) {
//                    if ($gap <= 0 || $gap > 3) {
//                        if ($carry['removedRecord'] === true) {
//
//                        }
//                    }
//                    return true;
//                }
//
//                if ($gap >= 0 || $gap < -3) {
//                    return false;
//                }
//                return true;
//
//            }, [
//                'removedRecord' => false,
//                'safe' => true,
//            ]);

            $safeGaps = $gaps->filter(function($gap) use ($ascendingOrder) {
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

            $unsafeGaps = count($gaps) - $safeGaps->count();

            if ($unsafeGaps <= 1) {
                $safeRecords += 1;
            }

        }

        return $safeRecords;
    }
}
