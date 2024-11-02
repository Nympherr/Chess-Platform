<?php

namespace Tests\Unit;

use App\Models\Chessboard;
use PHPUnit\Framework\TestCase;

class ChessboardTest extends TestCase
{
    public function test_2d_array_chessboard_created(): void
    {
        $chess_board_array = Chessboard::create_graphical_board();

        $this->assertIsArray($chess_board_array, "Chess board should be an array");

    }

    public function test_chessboard_rows_and_columns(): void
    {
        $chess_board_array = Chessboard::create_graphical_board();

        $this->assertCount(8, $chess_board_array, 'Chess board should be an array with 8 rows');
        foreach ($chess_board_array as $row) {
            $this->assertCount(8, $row, 'Each row in chess board should have 8 columns');
        }
    }

}
