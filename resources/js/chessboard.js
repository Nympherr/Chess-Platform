let selectedPiece = null;
const squares = document.querySelectorAll('.square');

squares.forEach(square => {
    square.addEventListener('click', function(){

        if(!selectedPiece){
            let piece = square.querySelector('.chess-piece');
            
            if(piece){
                selectedPiece = piece;
                selectedPiece.classList.add('bg-green-700');
            }
        } else {
            square.replaceChildren(selectedPiece);
            selectedPiece.classList.remove('bg-green-700');
            selectedPiece = null;
        }
    })
})