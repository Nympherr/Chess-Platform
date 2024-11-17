import '@chrisoakman/chessboard2/dist/chessboard2.min.js';
import '@chrisoakman/chessboard2/dist/chessboard2.min.css';
import { Chess } from 'chess.js'

Echo.channel('chess-room')
    .listen('PlayerPaired', (event) => {
        alert(`${event.player1} vs ${event.player2}`);
    });

const game = new Chess()

const boardConfig = {
  draggable: true,
  position: game.fen(),
  onDragStart,
  onDrop
}
const board = Chessboard2('myBoard', boardConfig)

const fenEl = byId('gameFEN')

updateStatus()

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
  
  let move;
  try {
    move = game.move({
      from: dropEvt.source,
      to: dropEvt.target,
      promotion: 'q',
    });
  } catch (error) {
    return 'snapback';
  }

  if (!move){
    return 'snapback';
  }
  
  board.fen(game.fen(), () => {
    updateStatus()
  })
}

function updateStatus () {
  fenEl.innerHTML = game.fen()
}

function byId (id) {
  return document.getElementById(id)
}