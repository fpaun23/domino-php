<?php

namespace App\Game;

interface PiecesStackInterface
{
    public function add(Piece $piece): array;

    public function takePieces(int $amount = 1): array;

    public function shuffle(): array;
}