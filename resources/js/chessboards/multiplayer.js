import '@chrisoakman/chessboard2/dist/chessboard2.min.js';
import '@chrisoakman/chessboard2/dist/chessboard2.min.css';
import { Chess } from 'chess.js'
import { startPlayerTime, stopPlayerTime } from './../countdownTimer';

Echo.channel('chess-room')
  .listen('.move.made', (event) => {
    broadcastMove(event);
    stopPlayerTime();
    startPlayerTime(playerTurn);
});

Echo.channel('chess-room')
  .listen('.clock.ended', (event) => {
    stopPlayerTime();
    clockEnd();
});


const game = new Chess();
let playerTurn = 'w';
let move = null;
const playerTurnElement = document.getElementById('player-move');
const gameEndText = document.getElementById('game-end-text');
const gameResultElement = document.getElementById('game-result');
const gameResultParentElement = document.getElementById('game-result-div');
const chessboard = document.getElementById('chessBoard');
const boardConfig = {
  draggable: true,
  position: game.fen(),
  onDragStart,
  onDrop
}
const board = Chessboard2('chessBoard', boardConfig);

window.addEventListener('update_pairing', () => {
  chessboard.classList.remove('pointer-events-none');
  playerTurnElement.style.display = 'block';
});

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

  chessboard.classList.add('pointer-events-none');
  checkGameState();

  Livewire.dispatch('update_move', [dropEvt.source, dropEvt.target, playerTurn]);
}

function checkGameState () {

  playerTurn = game.turn();

  if(game.isGameOver()){
    gameResultParentElement.style.display = 'block';
    playerTurnElement.style.display = 'none';
    gameEndText.style.display = 'block';

    if (game.isCheckmate() && playerTurn === 'w') {
      gameResultElement.textContent = 'Black wins! 0 - 1'
      Livewire.dispatch('end_game', ['0-1', game.fen(), game.pgn()]);
    } else if (game.isCheckmate() && playerTurn === 'b') {
      gameResultElement.textContent = 'White wins! 1 - 0'
      Livewire.dispatch('end_game', ['1-0', game.fen(), game.pgn()]);
    } else if (game.isStalemate() || game.isThreefoldRepetition() || game.isInsufficientMaterial() || game.isDraw()) {
      Livewire.dispatch('end_game', ['1/2', game.fen(), game.pgn()]);
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
}

function broadcastMove (moveEvent) {
  
  if(playerTurn == moveEvent['player_turn']){

    if(!game.isGameOver()){
      playerTurnElement.style.display = 'none';
    }
    return;
  }

  playerTurnElement.style.display = 'block';
  chessboard.classList.remove('pointer-events-none');
  makeMove(moveEvent['move_source'], moveEvent['move_target']);
  checkGameState();
}

function clockEnd() {
  chessboard.classList.add('pointer-events-none');
  gameResultParentElement.style.display = 'block';
  playerTurnElement.style.display = 'none';
  gameEndText.style.display = 'block';

  if (playerTurn === 'w') {
    document.getElementById('white-time').classList.remove('bg-green-500');
    document.getElementById('white-time').classList.add('bg-red-500');
    gameResultElement.textContent = 'Black wins! 0 - 1'
    Livewire.dispatch('end_game', ['0-1', game.fen(), game.pgn()]);
  } else if (playerTurn === 'b') {
    document.getElementById('black-time').classList.remove('bg-green-500');
    document.getElementById('black-time').classList.add('bg-red-500');
    gameResultElement.textContent = 'White wins! 1 - 0'
    Livewire.dispatch('end_game', ['1-0', game.fen(), game.pgn()]);
  }
  return;
}