let token = document.querySelector('meta[name="csrf-token"]').content;
let startTime = null;

function newGame() {
    let name = document.getElementById('name-input').value;
    name = name ? name : document.getElementById('player-name').innerText;
    let json = {name: name};
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            // console.log(this.response);
            document.getElementById('enter-name').style.display = 'none';
            document.getElementById('player-name').innerText = name;
            document.getElementById('play-form').hidden = false;
            document.getElementById('guess').value = '';
            document.getElementById('results').innerHTML = '';
            document.getElementById('play').style.display = 'block';
            startTime = Date.now();
        }
        if (this.readyState === 4 && this.status > 200) {
            alert(this.responseText);
        }
    }
    xhttp.open('POST', 'new-game');
    xhttp.setRequestHeader('Content-Type', 'application/json');
    xhttp.setRequestHeader('X-CSRF-TOKEN', token);
    xhttp.send(JSON.stringify(json));
}

function guessNumber() {
    let guessTime = Date.now() - startTime;
    let number = document.getElementById('guess').value;
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById('guess').value = '';
            // console.log(this.response);
            let res = JSON.parse(this.response);
            if (res.win === true) {
                let notice = document.createElement('div');
                notice.classList.add('alert', 'alert-success');
                notice.innerText = 'Поздравления! Познахте числото ' + number + ' с ' + res.tries + ' опита.';
                document.getElementById('results').prepend(notice);
                document.getElementById('play-form').hidden = true;
                getActiveTopList();
            }
            else {
                let par = document.createElement('p');
                let res = JSON.parse(this.response);
                par.innerText = number + ' => Бикове: ' + res.bulls + ', Крави: ' + res.cows;
                document.getElementById('results').prepend(par);
            }
        }
        if (this.readyState === 4 && this.status > 200) {
            alert(this.responseText);
            document.getElementById('guess').value = '';
        }
    }
    xhttp.open('GET', 'check/' + number + '?time=' + guessTime);
    xhttp.send();
}

function giveUp() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let number = this.responseText;
            let notice = document.createElement('div');
            notice.classList.add('alert', 'alert-danger');
            notice.innerText = 'Не успяхте да познаете числото ' + number + '.';
            document.getElementById('results').prepend(notice);
            document.getElementById('play-form').hidden = true;
        }
    }
    xhttp.open('GET', 'give-up');
    xhttp.send();
}

function editName(submit = false) {
    let element = document.getElementById('player-name');
    if (submit !== true) {
        let name = element.innerText;
        element.onclick = null;
        element.alt = name;
        element.innerHTML =
            '<input class="form-control-sm" type="text" value="' + name + '">' +
            '<i class="fas fa-check-circle" onclick="editName(true)"></i>';
    }
    else {
        let name = element.firstElementChild.value;
        if (!name.trim()) {
            alert('Не може да оставите името празно!');
            element.firstElementChild.value = element.alt;
            return;
        }
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                element.innerHTML = this.responseText;
                element.onclick = editName;
            }
            if (this.readyState === 4 && this.status > 200) {
                alert(this.responseText);
            }
        }
        xhttp.open('GET', 'edit-name/' + name);
        xhttp.send();
    }
}

function getTop(category) {
    let tbody = document.getElementById('top-tbody');
    tbody.innerHTML = '';
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let res = JSON.parse(this.response);
            let i = 1;
            for (let item of res) {
                let time = new Date(parseInt(item.time)).toISOString().slice(11,19);
                let tr = document.createElement('tr');
                let td0 = document.createElement('td');
                let td1 = document.createElement('td');
                let td2 = document.createElement('td');
                let td3 = document.createElement('td');
                td0.innerText = i.toString();
                td1.innerText = item.name;
                td2.innerText = item.tries;
                td3.innerText = time;
                tr.append(td0, td1, td2, td3);
                tbody.append(tr);
                i++;
            }
        }
    }
    xhttp.open('GET', 'get-top/' + category);
    xhttp.send();
}

function changeTab(category) {
    switch (category) {
        case 'tries':
            document.getElementById('top-times-link').classList.remove('active');
            document.getElementById('top-tries-link').classList.add('active');
            getTop('tries');
            break;
        case 'times':
            document.getElementById('top-tries-link').classList.remove('active');
            document.getElementById('top-times-link').classList.add('active');
            getTop('times');
            break;
    }
}

function getActiveTopList() {
    if (document.getElementById('top-times-link').classList.contains('active')) {
        getTop('times');
    }
    else {
        getTop('tries');
    }
}
