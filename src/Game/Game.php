<?php

namespace App\Game;

use App\Conf\Conf;
use App\Helpers\PiecesStackHelper;
use App\Helpers\PlayerHelper;
use App\Player\Player;

class Game implements GameInterface
{
    protected $players = [];
    protected $tableStack = [];
    protected $boardStack = [];
    protected $piecesStackHelper;
    protected $playerHelper;

    /**
     * @param PiecesStackHelper $piecesStackHelper
     * @param PlayerHelper $playerHelper
     */
    public function __construct(
        PiecesStackHelper $piecesStackHelper,
        PlayerHelper $playerHelper
    )
    {
        $this->piecesStackHelper = $piecesStackHelper;
        $this->playerHelper = $playerHelper;
    }

    /**
     * @var null
     */
    protected $winner = null;

    /**
     * @var string
     */
    protected $winType = '';

    /**
     * @var null
     */
    protected $startPiece = null;

    /**
     * @var null
     */
    protected $ownerStartPiece = null;

    /**
     * @var array
     */
    protected $playersRemainingTotal = [];

    /**
     * @param array $players
     */
    public function start(array $players)
    {
        //initialize table full stack, the board stack and the players
        $this->tableStack = $this->piecesStackHelper->createStack(Conf::GAME_PIECES);
        $this->boardStack = $this->piecesStackHelper->createStack();
        $this->players = $this->initPlayers($players);

        //restart the game until one of the players has a double in own stack
        $this->startPiece = $this->getStartPiece();
        if (null === $this->startPiece) {
            $this->start($players);
        }

        //rearange the players list so that the starting player to be the last in the list
        $this->reorderPlayers($this->getPieceOwnerIndex($this->startPiece));
        $this->ownerStartPiece = $this->players[$this->getPieceOwnerIndex($this->startPiece)];

        //add first piece on the board and mark it as played
        $this->boardStack->add($this->startPiece);
        $this->startPiece->setUnavailable();

        while (count($this->tableStack->getStack()) > 0) {
            foreach ($this->players as $player) {
                $this->play($player);
                if ($player->isWinner()) {
                    $this->winner = $player;
                    $this->winType= Conf::WIN_BEFORE_TABLE_STACK_EMPTY;
                    return;
                }
            }
        }

        if (count($this->tableStack->getStack()) == 0) {
            while (is_null($this->winner)) {
                foreach ($this->players as $player) {
                    if ($player->isWinner()) {
                        $this->winner = $player;
                        $this->winType = Conf::WIN_TABLE_STACK_EMPTY;
                        return;
                    }
                    if ($this->isBlocked()) {
                        $this->winner = $this->getWinnerBySum();
                        $this->winType = Conf::WIN_MIN_PIECES;
                        return;
                    }
                    $this->play($player);
                }
            }
        }
    }

    /**
     * @param Player $player
     */
    protected function play(Player $player)
    {
        $matchingPiece = $this->getMatchingPiece($player);

        //player has a matching piece in his stack
        if (null !== $matchingPiece) {

            $this->boardStack->addWithPosition($matchingPiece);
            $matchingPiece->setUnavailable();
            $player->setCanPlay();

        } else {
            $player->setCanPlay(false);

            //get a new piece from the table stack
            if (count($this->tableStack->getStack()) > 0) {
                $newPiece = $this->tableStack->takePieces();
                $player->getStack()->add($newPiece[0]);
            }
        }
    }

    /**
     * @param $player
     * @return mixed
     */
    protected function getMatchingPiece($player)
    {
        $matchingPieceFirst = $player->getStack()->getMatchingPiece($this->boardStack->getStack()[0]);

        if (count($this->boardStack->getStack()) > 1) {
            return
                null !== $matchingPieceFirst
                    ? $matchingPieceFirst
                    : $player->getStack()->getMatchingPiece($this->boardStack->getStack()[count($this->boardStack->getStack()) - 1], false)
                ;
        }
        return $matchingPieceFirst;
    }

    /**
     * @param array $players
     * @return array
     */
    protected function initPlayers(array $players): array
    {
        $initPlayers = [];
        foreach ($players as $index => $player) {
            $player = $this->playerHelper->createPlayer($player);
            $playerStack = $this->tableStack->takePieces(7);
            $player->setStack($this->piecesStackHelper->createStack($playerStack));
            $initPlayers[] = $player;
        }

        return $initPlayers;
    }

    /**
     * @return Piece|null
     */
    protected function getStartPiece(): ? Piece
    {
       $maxDoublePiece = null;

        foreach ($this->players as $player) {
            $maxPlayerPiece = $player->getStack()->getMaxDouble();
            if (null !== $maxPlayerPiece) {
                null !== $maxDoublePiece
                    ?   $maxPlayerPiece->getKey() > $maxDoublePiece->getKey() ? $maxDoublePiece = $maxPlayerPiece : ''
                    :   $maxDoublePiece = $maxPlayerPiece
                ;
            }
        }

        return $maxDoublePiece;
    }

    /**
     * @param Piece $piece
     * @return int|string
     */
    protected function getPieceOwnerIndex(Piece $piece)
    {
        foreach ($this->players as $index => $player) {
            if ($player->hasPieceinStack($piece)) {
                return $index;
            };
        }
        return -1;
    }

    /**
     * @param $offset
     */
    protected function reorderPlayers($offset): array
    {
        $staringPlayer = $this->players[$offset];
        unset($this->players[$offset]);
        $this->players[] = $staringPlayer;

        return $this->players;
    }

    /**
     * @return bool
     */
    protected function isBlocked(): bool
    {
        foreach($this->players as $player) {
            if ($player->canPlay()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return Player|null
     */
    protected function getWinnerBySum(): ? Player
    {
        $winner = null;
        foreach ($this->players as $player) {
            $piecesValue = $player->getStack()->getPiecesValue();
            $this->playersRemainingTotal[$player->getName()] = $piecesValue;
            if (is_null($winner)) {
                $winner = $player;
            } else if ($piecesValue < $winner->getStack()->getPiecesValue()) {
                    $winner = $player;
            }
        }
        return $winner;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return [
            Conf::OUTPUT_WINNER => $this->winner,
            Conf::OUTPUT_WIN_TYPE => $this->winType,
            Conf::OUTPUT_PLAYERS => $this->players,
            Conf::OUTPUT_START_PIECE => $this->startPiece,
            Conf::OUTPUT_OWNER_OF_START_PIECE => $this->ownerStartPiece,
            Conf::OUTPUT_FINAL_BOARD_STACK => $this->boardStack,
            Conf::OUTPUT_REMAINING_PIECES_VALUE => $this->playersRemainingTotal
        ];
    }
}
