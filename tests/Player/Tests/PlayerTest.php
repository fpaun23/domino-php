<?php

namespace App\Tests\Player\Tests;

use App\Conf\Conf;
use App\Game\PiecesStack;
use App\Helpers\PiecesStackHelper;
use App\Player\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    protected $player;
    static $name = 'bob';

    protected function setUp(): void
    {
        $this->player = new Player(self::$name);
        $playerStack = PiecesStackHelper::createStack(Conf::GAME_PIECES);
        $this->player->setStack($playerStack);
    }

    public function testName()
    {
        $this->assertEquals(self::$name, $this->player->getName());
    }

    public function testStack()
    {
        $this->assertInstanceOf(PiecesStack::class, $this->player->getStack());
    }

    public function testHasPieceInStack()
    {
        $this->assertTrue($this->player->hasPieceinStack($this->player->getStack()->getStack()[0]));
    }

    public function testCanPlay()
    {
        $this->assertTrue($this->player->canPlay());
        $this->assertEquals(false, $this->player->canPlay($this->player->setCanPlay(false)));
    }
}
