<?php

namespace App\Game;

use App\Conf\Conf;

class PiecesStack implements PiecesStackInterface
{
    /**
     * @var array
     */
    protected $stack = [];

    /**
     * @param array $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = $stack;
    }

    /**
     * @param Piece $piece
     * @param bool $prepend
     * @return array
     */
    public function add(Piece $piece, bool $prepend = false): array
    {
        if ($prepend) {
            array_unshift($this->stack, $piece);
        } else {
            $this->stack[] = $piece;
        }
        return $this->stack;
    }

    /**
     * @return array
     */
    public function shuffle(): array
    {
        shuffle($this->stack);
        return $this->stack;
    }

    /**
     * @param int $amount
     * @return array
     */
   public function takePieces(int $amount = 1): array
   {
       $this->shuffle();
       return array_splice($this->stack, 0, $amount);
   }

    /**
     * @return array
     */
   public function getStack(): array
   {
       return $this->stack;
   }

    /**
     * @return Piece|null
     */
   public function getMaxDouble(): ? Piece
   {
       $maxDoubleValue = -1;
       $maxDoublePiece = null;

       foreach ($this->stack as $piece) {
           $key = $piece->getKey();
           if (in_array($key, Conf::DOUBLE_KEYS) && $maxDoubleValue < $key) {
               $maxDoubleValue = $key;
               $maxDoublePiece = $piece;
           }
       }
       return $maxDoublePiece;
   }

    /**
     * @return array
     */
    public function getAvailablePieces(): array
    {
        $playablePieces = [];

        foreach ($this->stack as $piece) {
            if ($piece->isAvailable()) {
                $playablePieces[] = $piece;
            }
        }

        return $playablePieces;
    }

    /**
     * @param Piece $piece
     * @param bool $first
     * @return Piece|null
     */
    public function getMatchingPiece(Piece $piece, bool $first = true): ? Piece
    {
        foreach ($this->getAvailablePieces() as $playablePiece) {
            $isMatching = in_array($piece->getValues()[intval(!$first)], $playablePiece->getValues());
            if ($isMatching) {
                return $playablePiece;
            }
        }
        return null;
    }

    /**
     * @param Piece $piece
     * @return array
     */
    public function addWithPosition(Piece $piece): array
    {
       if (count($this->stack) > 0) {

           $lastPiece = end($this->stack);
           $firstPiece = $this->stack[0];

           if ($lastPiece->getValues()[1] == $piece->getValues()[0]) {
               return $this->add($piece);
           }

           if ($lastPiece->getValues()[1] == $piece->getValues()[1]) {
               return $this->add($piece->reverseValues());
           }

           if ($firstPiece->getValues()[0] == $piece->getValues()[1]) {
               return $this->add($piece, true);
           }

           if ($firstPiece->getValues()[0] == $piece->getValues()[0]) {
               return $this->add($piece->reverseValues(), true);
           }
       }

        return $this->stack;
    }

    /**
     * @return int
     */
    public function getPiecesValue(): int
    {
        return array_reduce( $this->getAvailablePieces(), function ($sum, $piece) {
            list($value1, $value2) = $piece->getValues();
            $sum += intval($value1) + intval($value2);
            return $sum;
        }, 0);
    }
}