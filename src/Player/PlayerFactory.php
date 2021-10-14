<?php

namespace App\Player;

class PlayerFactory
{
    /**
     * @param string $name
     * @return Player
     */
    public static function create(string $name): Player
    {
        return new Player($name);
    }
}