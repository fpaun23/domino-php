<?php

namespace App\Helpers;

use App\Conf\Conf;

class Output implements OutputInterface
{

    protected $output = [];

    /**
     * @param array $data
     * @return array
     */
    public function output(array $data): array
    {
        foreach($data as $key => $value) {
            $this->format($key, $value);
        }
        return $this->output;
    }

    /**
     * @param $key
     * @param $value
     */
    public function format($key, $value)
    {
        switch ($key) {

            case Conf::OUTPUT_START_PIECE:
                $this->output[$key]= $this->formatPiece($value);
                break;
            case Conf::OUTPUT_FINAL_BOARD_STACK:
                $this->output[$key]= $this->formatStack($value);
                break;
            case Conf::OUTPUT_PLAYERS:
                $this->output[$key]= $this->formatPlayers($value);
                if ($this->output[Conf::OUTPUT_WIN_TYPE] == Conf::WIN_MIN_PIECES) {
                    $this->output[Conf::OUTPUT_REMAINING_PIECES] = $this->formatPlayersAvailablePieces($value);
                }
                break;
            case in_array($key,[Conf::OUTPUT_OWNER_OF_START_PIECE, Conf::OUTPUT_WINNER]):
                $this->output[$key] = $value->getName();
                break;
            case Conf::OUTPUT_REMAINING_PIECES_VALUE:
                if ($this->output[Conf::OUTPUT_WIN_TYPE] == Conf::WIN_MIN_PIECES) {
                    $this->output[Conf::OUTPUT_REMAINING_PIECES_VALUE] = $value;
                }
                break;
            default:
                $this->output[$key] = $value;
                break;
        }
    }

    /**
     * @param $stack
     * @return string
     */
    public function formatStack($stack): string
    {
        $formatted = '';
        foreach ($stack->getStack() as $piece) {
                $formatted .= $this->formatPiece($piece);
        }
        return $formatted;
    }

    /**
     * @param $players
     * @return array
     */
    public function formatPlayersAvailablePieces($players): array
    {
        $formatted = [];
        foreach($players as $player) {
            $pieces = '';
            foreach($player->getStack()->getAvailablePieces() as $piece) {
                $pieces .= $this->formatPiece($piece);
            }
            $formatted[$player->getName()] = $pieces;
        }
        return $formatted;
    }

    /**
     * @param $players
     * @return array
     */
    public function formatPlayers($players): array
    {
        $formatted = [];
        foreach($players as $player) {
            $formatted[$player->getName()] = $this->formatStack($player->getStack());
       }
        return $formatted;
    }

    /**
     * @param $piece
     * @return string
     */
    public function formatPiece($piece): string
    {
        $values = $piece->getValues();
        return "[" . $values[0] . ":" . $values[1]  ."]";
    }
}