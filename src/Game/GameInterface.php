<?php

namespace App\Game;

use App\Player\Player;

interface GameInterface
{
    public function start(array $players);
}