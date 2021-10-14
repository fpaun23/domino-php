<?php

namespace App\Game;

class PieceFactory
{
    /**
     * @param string $name
     * @return Player
     */
    public static function create(array $piece): Piece
    {
        return new Piece($piece);
    }
}