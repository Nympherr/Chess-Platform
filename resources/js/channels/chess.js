
Echo.channel('chess-room')
  .listen('.players.paired', (event) => {
    Livewire.dispatch('update_pairing', [event.player1, event.player2]);
});