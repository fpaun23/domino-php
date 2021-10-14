<?php

namespace App\Tests\Game\Tests;

use PHPUnit\Framework\TestCase;
use App\Game\Piece;

class PieceTest extends TestCase
{

    protected $piece;
    static $values = [0, 1];

    protected function setUp(): void
    {
        $this->piece = new Piece(self::$values);
    }

    public function testIsAvailable()
    {
        $this->assertTrue($this->piece->isAvailable());
    }

    public function testIsNotAvailable()
    {
        $this->assertEquals(false, $this->piece->isAvailable($this->piece->setUnavailable()));
    }

    public function reverseValues()
    {
        $initialPiece = $this->piece;
        $this->assertEquals($initialPiece->getValues(), array_reverse($this->piece->getValues()));
    }
}