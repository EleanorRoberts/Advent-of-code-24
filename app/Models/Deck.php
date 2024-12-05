<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deck extends Model
{
    public function games(): HasMany
    {
        return $this->hasMany(ScratchcardGame::class);
    }

    public function __construct(
        array $startingCards,
    ) {
        $games = [];
        foreach ($startingCards as $line) {
            $game = new ScratchcardGame($this, $line);
            $games[] = $game;
        }

        $this->setRelation('games', collect($games));
    }
}
