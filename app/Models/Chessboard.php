<?php

namespace App\Models;

class Chessboard
{
    /**
     * Creates 2D array (8x8) for displaying later chess board
     */
    public static function create_graphical_board(): array
    {
        $board_data = array();

        for($rank = 0; $rank < 8; $rank++){
            for($file = 0; $file < 8; $file++){
                $square_color = ($file + $rank) % 2 == 0 ? 'darkCol' : 'lightCol';

                $board_data[$rank][$file] = [
                    'square_color' => $square_color,
                    'piece' => 0,
                ];
            }
        }

        // TODO make this more effective
        $piece_positions = self::convert_fen_to_readable_format();

        foreach($piece_positions as $rank => $piece_position){
            foreach($piece_position as $file => $piece){
                $board_data[$rank][$file] = array_merge($board_data[$rank][$file], [
                    'piece' => $piece,
                ]);
            };
        };

        // Reversing for white to start from index 0 (instead of black)
        return array_reverse($board_data);
    }

    /**
     * Converts FEN format (Forsyth–Edwards Notation) to chess position
     * Returns each piece placement on board in 2D array
     */
    private static function convert_fen_to_readable_format($board_position = null): array
    {

        $fen = $board_position == null ? 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR' : $board_position;
        
        $piece_positions = array();

        $piece_values = [
            'P' => 1,
            'p' => -1,
            'N' => 2,
            'n' => -2,
            'B' => 3,
            'b' => -3,
            'R' => 4,
            'r' => -4,
            'Q' => 5,
            'q' => -5,
            'K' => 6,
            'k' => -6 
        ];

        $rank_piece_positions = array_reverse(explode('/', $fen));

        for($rank = 7; $rank >= 0; $rank--){
            $file = 0;

            $individual_pieces = str_split($rank_piece_positions[$rank]);

            foreach($individual_pieces as $piece){

                if(is_numeric($piece)) {
                    $file += (int)$piece;
                } else {
                    $piece_positions[$rank][$file] = $piece_values[$piece];
                    $file += 1;
                }
            }
        }

        return $piece_positions;
    }

    public static function get_piece_icon($piece_value)
    {

        $pieces = [
            '1' => asset('chess-pieces/white-pawn.png'),
            '-1' => asset('chess-pieces/black-pawn.png'),
            '2' => asset('chess-pieces/white-knight.png'),
            '-2' => asset('chess-pieces/black-knight.png'),
            '3' => asset('chess-pieces/white-bishop.png'),
            '-3' => asset('chess-pieces/black-bishop.png'),
            '4' => asset('chess-pieces/white-rook.png'),
            '-4' => asset('chess-pieces/black-rook.png'),
            '5' => asset('chess-pieces/white-queen.png'),
            '-5' => asset('chess-pieces/black-queen.png'),
            '6' => asset('chess-pieces/white-king.png'),
            '-6' => asset('chess-pieces/black-king.png'),

        ];

        return $piece_value == '0' ? '' : $pieces[$piece_value];
    }
}