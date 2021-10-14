<?php

namespace App\Conf;

final class Conf
{
    const MIN_PLAYERS = 2;
    const MAX_PLAYERS = 4;
    const GAME_PIECES = array(
        '0,0',
        '1,1',
        '2,2',
        '3,3',
        '4,4',
        '5,5',
        '6,6',
        '0,1',
        '0,2',
        '0,3',
        '0,4',
        '0,5',
        '0,6',
        '1,2',
        '1,3',
        '1,4',
        '1,5',
        '1,6',
        '2,3',
        '2,4',
        '2,5',
        '2,6',
        '3,4',
        '3,5',
        '3,6',
        '4,5',
        '4,6',
        '5,6'
    );
    const DOUBLE_KEYS = array(0, 11, 22, 33, 44, 55, 66);
    const WIN_BEFORE_TABLE_STACK_EMPTY = 'player wins with no more available pieces in hand and table stack not empty';
    const WIN_TABLE_STACK_EMPTY = 'player wins with no more available pieces in hand and table stack is empty';
    const WIN_MIN_PIECES = 'player wins with min values of the pieces';
    const OUTPUT_START_PIECE = 'start_piece';
    const OUTPUT_FINAL_BOARD_STACK = 'final_board_stack';
    const OUTPUT_PLAYERS = 'players';
    const OUTPUT_WIN_TYPE = 'win_type';
    const OUTPUT_REMAINING_PIECES = 'remaining_pieces';
    const OUTPUT_WINNER = 'winner';
    const OUTPUT_OWNER_OF_START_PIECE = 'owner_of_start_piece';
    const OUTPUT_REMAINING_PIECES_VALUE = 'remaining_pieces_value';
}
