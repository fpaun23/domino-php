<?php

namespace App\Player;

use App\Game\Piece;
use App\Game\PiecesStack;

class Player implements PlayerInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $stack = [];

    /**
     * @var bool
     */
    private $canPlay = true;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param PiecesStack $stack
     */
    public function setStack(PiecesStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @return PiecesStack
     */
    public function getStack(): PiecesStack
    {
        return $this->stack;
    }

    /**
     * Checks if playable pieces are left
     * @return bool
     */
    public function isWinner(): bool
    {
        return (bool) count($this->stack->getAvailablePieces()) == 0;
    }

    /**
     * @param bool $canPlay
     * @return bool
     */
    public function setCanPlay(bool $canPlay = true): bool
    {
        $this->canPlay = $canPlay;
        return $this->canPlay;
    }

    /**
     * @return bool
     */
    public function canPlay(): bool
    {
        return $this->canPlay;
    }

    /**
     * Checks if specific Piece is in stack
     * @param Piece $piece
     * @return bool
     */
    public function hasPieceinStack(Piece $piece): bool
    {
        return in_array($piece, $this->stack->getStack(), true);
    }
}