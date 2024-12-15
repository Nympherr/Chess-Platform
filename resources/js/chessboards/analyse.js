import '@chrisoakman/chessboard2/dist/chessboard2.min.js';
import '@chrisoakman/chessboard2/dist/chessboard2.min.css';
import { Chess } from 'chess.js'

// Loading game data
const game = new Chess();
game.loadPgn(window.gameData.game_history);
const moves = game.history({ verbose: true });

const boardConfig = {
    position: game.fen(),
}
const board = Chessboard2('chessBoard', boardConfig);

const LAST_MOVE_INDEX = moves.length - 1;
const STARTING_POSITION = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";

// Dynamic data
let currentMoveIndex = LAST_MOVE_INDEX;

// DOM elements
const previousMoveButton = document.getElementById('previous-move');
const nextMoveButton = document.getElementById('next-move');

function goToPreviousMove() {
    if(currentMoveIndex == -1) {
        board.position(STARTING_POSITION);
        return;
    }

    currentMoveIndex -= 1;

    if(currentMoveIndex == -1){
        return;
    }
    let previousMoveFen = moves[currentMoveIndex].after;
    board.position(previousMoveFen);
}

function goToNextMove() {
    if(currentMoveIndex == LAST_MOVE_INDEX) {
        return;
    }

    currentMoveIndex += 1;

    let nextMoveFen = moves[currentMoveIndex].after;
    board.position(nextMoveFen);
}

// Event Listeners
previousMoveButton.addEventListener('click', goToPreviousMove);
nextMoveButton.addEventListener('click', goToNextMove);
