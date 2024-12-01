import axios from 'axios';
import '@chrisoakman/chessboard2/dist/chessboard2.min.js';
import '@chrisoakman/chessboard2/dist/chessboard2.min.css';
import { Chess } from 'chess.js'

const game = new Chess();
let move = null;
const boardConfig = {
  draggable: true,
  position: game.fen(),
  onDragStart,
  onDrop
}
const board = Chessboard2('chessBoard', boardConfig);
const boardElement = document.getElementById('chessBoard');
const playerColorElement = document.getElementById('player-color');
const sideMoveElement = document.getElementById('player-move');
const playerColor = decidePlayerColor();
let sideToMove = 'w';
changeWhoseTurn('w');

function onDragStart (dragStartEvt) {

  if (game.isGameOver()) return false

  if (game.turn() === 'w' && !isWhitePiece(dragStartEvt.piece)) return false
  if (game.turn() === 'b' && !isBlackPiece(dragStartEvt.piece)) return false

  const legalMoves = game.moves({
    square: dragStartEvt.square,
    verbose: true
  })

  legalMoves.forEach((move) => {
    board.addCircle(move.to)
  })
}

function isWhitePiece (piece) { return /^w/.test(piece) }
function isBlackPiece (piece) { return /^b/.test(piece) }

function onDrop (dropEvt) {
    
  board.clearCircles()

  if(dropEvt.source == dropEvt.target){
    return;
  }

  const moveResult = makeMove(dropEvt.source, dropEvt.target);

  if (moveResult === 'snapback') {
    return 'snapback';
  }

  sendMoveToServer();
}

function checkGameState () {

  sideToMove = game.turn();

  if(game.isGameOver()){

    if (game.isCheckmate() && sideToMove === 'w') {
      gameResultElement.textContent = 'Black wins! 0 - 1'
    } else if (game.isCheckmate() && sideToMove === 'b') {
      gameResultElement.textContent = 'White wins! 1 - 0'
    } else if (game.isStalemate()) {
      gameResultElement.textContent = 'Game is drawn! 1/2'
    } else if (game.isThreefoldRepetition()) {
      gameResultElement.textContent = 'Game is drawn! 1/2'
    } else if (game.isInsufficientMaterial()) {
      gameResultElement.textContent = 'Game is drawn! 1/2'
    } else if (game.isDraw()) {
      gameResultElement.textContent = 'Game is drawn! 1/2'
    }
    return;
  }

  changeWhoseTurn(sideToMove);
}

function makeMove (sourcePosition, targetPosition) {

  try {
    move = game.move({
      from: sourcePosition,
      to: targetPosition,
      promotion: 'q',
    });
  } catch (error) {
    return 'snapback';
  }

  if (!move){
    return 'snapback';
  }

  board.position(game.fen());

  checkGameState();
}

const sendMoveToServer = async () => {

    axios.post('/get-stockfish-move', { gamePosition: game.fen() }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(response => {
        console.log(response.data.computerMove);
        game.move(response.data.computerMove);
        board.position(game.fen());
        sideToMove = game.turn();
        changeWhoseTurn();
    })
    .catch(error => {
        console.error('Error:', error);
    });
};

function decidePlayerColor() {
    const isWhite = () => Math.random() >= 0.5;

    if(isWhite()){
        playerColorElement.textContent += 'white';
        return 'w';
    } else {
        playerColorElement.textContent += 'black';
        return 'b';
    }
}

function changeWhoseTurn(){

    if(sideToMove == playerColor){
        sideMoveElement.textContent = 'Your move';
        boardElement.classList.remove('pointer-events-none');
    } else {
        sideMoveElement.textContent = 'Computer is thinking';
        boardElement.classList.add('pointer-events-none');
    }
}

// Only when player starts as white
if(playerColor == 'b'){
    sendMoveToServer();
}