<?php

namespace App\Game;

class PiecesStackFactory
{
    public static function create(array $piece): PiecesStack
    {
        return new PiecesStack($piece);
    }
}