<?php

namespace App\Helpers;

use App\Player\Player;
use App\Player\PlayerFactory;

class PlayerHelper
{
    public static function createPlayer(string $playerName): Player
    {
        return PlayerFactory::create($playerName);
    }
}