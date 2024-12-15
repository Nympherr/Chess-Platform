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
const resultElement = document.getElementById('game-result');
const playerColor = decidePlayerColor();
let gameEnded = false;
let sideToMove = 'w';
let result = null;
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

    gameEnded = true;
    resultElement.style.display = 'block';
    sideMoveElement.style.display = 'none';

    if (game.isCheckmate() && sideToMove === 'w' && playerColor === 'w') {
        resultElement.textContent = 'You lost! Computer won!'
        result = '0-1';
    } else if (game.isCheckmate() && sideToMove === 'b' && playerColor === 'w') {
        resultElement.textContent = 'You won! Computer lost!'
        result = '1-0';
    } else if (game.isStalemate()) {
        resultElement.textContent = 'Game is drawn! 1/2'
        result = '1/2';
    } else if (game.isThreefoldRepetition()) {
        resultElement.textContent = 'Game is drawn! 1/2'
        result = '1/2';
    } else if (game.isInsufficientMaterial()) {
        resultElement.textContent = 'Game is drawn! 1/2'
        result = '1/2';
    } else if (game.isDraw()) {
        resultElement.textContent = 'Game is drawn! 1/2'
        result = '1/2';
    }

    sendGameDataToServer();

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

    if(gameEnded){
        return;
    }
    axios.post('/get-stockfish-move', { gamePosition: game.fen() }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(response => {
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

function sendGameDataToServer(){
  
  axios.post('/finish-stockfish-game', {
    id: window.userData,
    color: playerColor,
    fen: game.fen(),
    result: result,
    history: game.pgn(),
  },{
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    }
  }).then(response => {
    window.alert(response.data.completed);
  })
  .catch(error => {
    console.error('Error:', error);
  });
}