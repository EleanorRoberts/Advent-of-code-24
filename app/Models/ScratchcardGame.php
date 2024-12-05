<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScratchcardGame extends Model
{
    public $fillable = [
        'deck_id'
    ];
    public int $gameNumber;
    private array $winningNumbers;
    private array $yourNumbers;

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class, 'deck_id');
    }
    public function __construct(
        public ?Deck $deck = null,
        private string $fullGameDetails = '',
        public int $copies = 1,
    ) {
        [ $gameNo, $winningNumbers, $yourNumbers ] = preg_split(
            '/[:|]/',
            $this->fullGameDetails,
        );

        $this->gameNumber = (int)preg_replace('/\D/', '', $gameNo);
        $this->winningNumbers = $this->formatNumbers($winningNumbers);
        $this->yourNumbers = $this->formatNumbers($yourNumbers);
    }

    public function calculateNumberOfWins()
    {
        $winningNumberMatches = array_intersect($this->winningNumbers, $this->yourNumbers);

        return count($winningNumberMatches);
    }

    public function starOneGameTotal(int $wins)
    {
        if ($wins === 0) {
            return 0;
        }

        $gameTotal = 1; // The first card is worth 1 point, then times 2

        for ($i = 1; $i < ($wins); $i++) {
            $gameTotal = $gameTotal * 2;
        }

        return $gameTotal;
    }

    public function starTwoGameTotal(int $wins)
    {
        for ($i = 1; $i <= $wins; $i++) {
            dump($this->deck->games());
            dd($this->deck->games->firstWhere(['gameNumber', $this->gameNumber + $i]));
            $addCopiesTo = $this->deck->games->where('gameNumber', $this->gameNumber + $i);
//            if ($addCopiesTo !== null) {
//                $addCopiesTo[0]->incrementCopies($this->copies);
//            }
        }
    }

    public function incrementCopies($incrementBy = 1): void
    {
        if ($incrementBy === 1) {
            $this->copies++;

            return;
        }

        $this->copies += $incrementBy;
    }

    private function formatNumbers(string $numbers): array
    {
        return array_values(array_filter(preg_split('/\D/', $numbers)));
    }
}
