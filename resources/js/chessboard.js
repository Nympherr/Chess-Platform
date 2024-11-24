import '@chrisoakman/chessboard2/dist/chessboard2.min.js';
import '@chrisoakman/chessboard2/dist/chessboard2.min.css';
import { Chess } from 'chess.js'

Echo.channel('chess-room')
  .listen('.move.made', (event) => {
    broadcastMove(event);
});

const game = new Chess();
let playerTurn = 'w';
let move = null;
const gameResultElement = document.getElementById('game-result');
const gameResultParentElement = document.getElementById('game-result-div');
const playerTurnElement = document.getElementById('player-turn');
const playerTurnParentElement = document.getElementById('player-turn-div');
const boardConfig = {
  draggable: true,
  position: game.fen(),
  onDragStart,
  onDrop
}
const board = Chessboard2('chessBoard', boardConfig);

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

  checkGameState();

  Livewire.dispatch('update_move', [dropEvt.source, dropEvt.target, playerTurn]);
}

function checkGameState () {

  playerTurn = game.turn();

  if(game.isGameOver()){
    gameResultParentElement.style.display = 'block';
    playerTurnParentElement.style.display = 'none';
    if (game.isCheckmate() && playerTurn === 'w') {
      gameResultElement.textContent = 'Black wins! 0 - 1'
    } else if (game.isCheckmate() && playerTurn === 'b') {
      gameResultElement.textContent = 'White wins! 1 - 0'
    } else if (game.isStalemate() && playerTurn === 'w') {
      gameResultElement.textContent = 'Game is drawn! 1/2'
    } else if (game.isStalemate() && playerTurn === 'b') {
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

  switch(playerTurn){
    case 'w':
      playerTurnElement.textContent = 'white';
      break;
    case 'b':
      playerTurnElement.textContent = 'black';
      break;
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
    return;
  }

  makeMove(moveEvent['move_source'], moveEvent['move_target']);
  checkGameState();
}