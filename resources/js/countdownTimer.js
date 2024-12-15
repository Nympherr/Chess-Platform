let timeIntervalId;
const whitePlayerClock = document.getElementById('white-time');
const blackPlayerClock = document.getElementById('black-time')

function startTimer(duration, display) {
    var start = Date.now(),
        diff,
        minutes,
        seconds;
    function timer() {

        diff = duration - (((Date.now() - start) / 1000) | 0);
        
        minutes = (diff / 60) | 0;
        seconds = (diff % 60) | 0;
        
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds; 

        if (diff <= 0) {
            console.log("TIME ENDED");
            Livewire.dispatch('clock_finished', ['test']);
        }

    };
    timer();
    timeIntervalId = setInterval(timer, 1000);
}

export function startPlayerTime(playerColor = 'w') {

    let display = playerColor == 'w' ? whitePlayerClock : blackPlayerClock;

    let count = getTime(display.textContent.trim());

    changeClockColor(playerColor);
    startTimer(count,display);

    //TODO
    if(playerColor == 'w') {
        console.log("white's clock running");
    } else {
        console.log("black's clock running");
    }
}

export function stopPlayerTime() {
    clearInterval(timeIntervalId);
}

function getTime(timeElement) {

    const timeParts = timeElement.split(":");
    let minutes = parseInt(timeParts[0], 10);
    let seconds = parseInt(timeParts[1], 10);

    return (minutes * 60) + seconds;
}

function changeClockColor(playerColor) {
    whitePlayerClock.classList.toggle('bg-green-500', playerColor === 'w');
    blackPlayerClock.classList.toggle('bg-green-500', playerColor !== 'w');
}

//TODO
console.log("Clock import works") 