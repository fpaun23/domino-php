<?php

namespace App\Game;

/**
 * Class PieceInterface
 */
interface PieceInterface
{
    /**
     * @return array
     */
    public function getValues(): array;

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @return Piece
     */
    public function setUnavailable(): Piece;
}