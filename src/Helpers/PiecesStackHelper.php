<?php

namespace App\Helpers;

use App\Game\Piece;
use App\Game\PieceFactory;
use App\Game\PiecesStack;
use App\Game\PiecesStackFactory;

class PiecesStackHelper
{
    public static function createStack(array $pieces = []): PiecesStack
    {
        $piecesStack = [];

        foreach ($pieces as $index => $piece) {
            $piecesStack[] = $piece instanceof Piece ? $piece : PieceFactory::create(explode(",", $piece));
        }

        return PiecesStackFactory::create($piecesStack);
    }
}