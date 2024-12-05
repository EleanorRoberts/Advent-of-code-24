<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DayOne extends Command
{
    private string $inputFile = '/assets/day_one_input';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'day:one';

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

        if ($this->confirm('Run the second star command?', true)) {
            $total = $this->secondStar($input);
        } else {
            $total = $this->firstStar($input);
        }

        $this->info('The total difference is ' . $total);

        return Command::SUCCESS;
    }

    private function firstStar($input)
    {
        $total = 0;
        [ $listOne, $listTwo ] = $this->createLists($input);


        $listOne = array_values(array_sort($listOne));
        $listTwo = array_values(array_sort($listTwo));

        foreach ($input as $key => $line) {
            $diff = 0;
            $numOne = $listOne[$key];
            $numTwo = $listTwo[$key];

            if ($numOne >= $numTwo) {
                $diff = $numOne - $numTwo;
            } else {
                $diff = $numTwo - $numOne;
            }

            if ($diff < 0) {
                dd($line, $numTwo, $numOne);
            }

            $total += $diff;
        }

        return $total;
    }

    private function secondStar($input)
    {
        $total = 0;
        [ $listOne, $listTwo ] = $this->createLists($input);

        foreach ($listOne as $item) {
            $similarityScore = 0;

            $count = count(array_filter($listTwo, fn ($li) => $item === $li));

            if ($count > 0) {
                $similarityScore = $item * $count;
            }

            $total += $similarityScore;
        }

        return $total;
    }

    private function createLists($input)
    {
        $listOne = [];
        $listTwo = [];

        foreach ($input as $line) {
            $numbers = explode(' ', $line);

            $listOne[] = (int)$numbers[0];
            $listTwo[] = (int)array_pop($numbers);
        }

        return [ $listOne, $listTwo ];
    }
}
