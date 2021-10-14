<?php

namespace App\Tests\Game\Tests;

use App\Game\Piece;
use App\Game\PieceFactory;
use App\Game\PiecesStack;
use PHPUnit\Framework\TestCase;
use App\Helpers\PiecesStackHelper;
use App\Conf\Conf;

class PiecesStackTest extends TestCase
{
    protected $piecesStack;
    protected $piece;
    static $values = [9, 9];

    protected function setUp(): void
    {
        $this->piece = new Piece(self::$values);

        $pieces = [];
        foreach (Conf::GAME_PIECES as $piece) {
            $pieces[] = PieceFactory::create(explode(",", $piece));
        }
        $this->piecesStack = new PiecesStack($pieces);
    }

    public function testAddPieceToOfStack()
    {
        $this->piecesStack->add($this->piece);
        $this->assertEquals(count(Conf::GAME_PIECES) + 1, count($this->piecesStack->getStack()));
        $this->assertEquals($this->piece, $this->piecesStack->getStack()[count($this->piecesStack->getStack()) - 1]);
    }

    public function testPrependPieceToStack()
    {
        $nbPiecesInStack = count($this->piecesStack->getStack());
        $firstPieceInStack = $this->piecesStack->getStack()[0];
        $firstValue = $firstPieceInStack->getValues()[0];

        $pieceToPrepend = new Piece([$firstValue, '9']);
        $this->piecesStack->addWithPosition($pieceToPrepend );

        $this->assertEquals($nbPiecesInStack + 1, count($this->piecesStack->getStack()));
        $this->assertEquals($pieceToPrepend, $this->piecesStack->getStack()[0]);

    }

    public function testAddPieceToStackEnd()
    {
        $nbPiecesInStack = count($this->piecesStack->getStack());
        $pieces = $this->piecesStack->getStack();
        $lastPieceInStack = end($pieces);
        $lastValue = $lastPieceInStack->getValues()[1];

        $pieceToAdd = new Piece([$lastValue, '9']);

        $this->piecesStack->addWithPosition($pieceToAdd);
        $newStack = $this->piecesStack->getStack();

        $this->assertEquals($nbPiecesInStack + 1, count($this->piecesStack->getStack()));
        $this->assertEquals($pieceToAdd, end($newStack));
    }

    public function testGetAvailablePieces()
    {
        $initialAvailablePiecesInStack = $this->piecesStack->getAvailablePieces();
        $firstPieceInStack = $this->piecesStack->getStack()[0];
        $firstPieceInStack->setUnavailable();
        $afterChangeAvailablePiecesInStack =  $this->piecesStack->getAvailablePieces();

        $this->assertEquals(count($initialAvailablePiecesInStack), count($afterChangeAvailablePiecesInStack) + 1);
    }
}