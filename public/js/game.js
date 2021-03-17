function newGame() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response);
            document.getElementById('enter-name').style.display = 'none';
            document.getElementById('play').style.display = 'block';
        }
        if (this.readyState === 4 && this.status > 200) {
            alert(this.responseText);
        }
    }
    xhttp.open('GET', 'new-game');
    xhttp.send();
}

function guessNumber() {
    let number = document.getElementById('guess').value;
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            console.log(this.response);
            let par = document.createElement('p');
            if (this.responseText === 'win') {
                par.innerText = 'Познахте числото ' + number + '!';
            }
            else {
                res = JSON.parse(this.response);
                par.innerText = number + ' => Бикове: ' + res.bulls + ', Крави: ' + res.cows;
            }
            document.getElementById('results').appendChild(par);
        }
        if (this.readyState === 4 && this.status > 200) {
            alert(this.responseText);
        }
    }
    xhttp.open('GET', 'check/' + number);
    xhttp.send();
}

function justAFunction() {
    alert ("I am a new function!");
}
