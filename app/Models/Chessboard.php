<?php

namespace App\Models;

class Chessboard
{
    public static function create_graphical_board()
    {
        $board_data = array();

        for($rank = 0; $rank < 8; $rank++){
            for($file = 0; $file < 8; $file++){
                $square_color = ($file + $rank) % 2 == 0 ? 'darkCol' : 'lightCol';

                $board_data[$rank][$file] = [
                    'square_color' => $square_color,
                ];
            }
        }

        return $board_data;
    }
}