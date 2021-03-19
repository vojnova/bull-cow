<!doctype html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Бикове и крави</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css"
        rel="stylesheet"
    />
    <!-- MDB -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.js"
    ></script>
    <link rel="stylesheet" href="/css/game.css">
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/game.js"></script>
{{--    <script src="{{asset('public/js/app.js')}}"></script>--}}
</head>
<body onload="getTopLists()">
<nav class="navbar navbar-light bg-light">
    <div class="container-fluid justify-content-between">
        <span class="navbar-brand">Бикове и крави</span>
        <button class="btn btn-outline-primary me-2" type="button" onclick="newGame()">Нова игра</button>
        <span class="navbar-brand">
            <i class="fas fa-user-secret"></i>
            <span style="cursor: pointer" id="player-name" onclick="editName()">{{session('name')}}</span>
        </span>
    </div>
</nav>
<main class="container">
    <div class="card text-center w-50" id="enter-name" @if(session('name')) style="display: none" @endif >
        <div class="card-body">
            <h2 class="card-title">Добре дошли!</h2>
            <h5 class="card-text">Моля, въведете своето име, за да започнете.</h5>
            <form onsubmit="event.preventDefault(); newGame()">
                <div class="mb-3">
                    <input class="form-control" type="text" id="name-input" minlength="3" required>
                </div>
                <button class="btn btn-success" type="submit">Нова игра</button>
            </form>
        </div>
    </div>
    <div class="card w-50" id="play" style="display: none">
        <div class="card-body">
            <h3 class="card-title">Опитайте се да познаете числото, като въведете 4 уникални цифри:</h3>
            <form class="input-group mb-3" onsubmit="event.preventDefault(); guessNumber()" id="play-form">
                <input class="form-control" type="text" maxlength="4" pattern="^(?:([0-9])(?!.*\1)){4}$" id="guess" required>
                <button class="btn btn-success" type="submit">Познай</button>
                <button class="btn btn-danger" type="button" onclick="giveUp()">Предавам се</button>
            </form>
            <div id="results" class="card-text"></div>
        </div>
    </div>
    <div style="display: flex; flex-direction: column">
        <div class="card text-dark bg-light mb-3" style="max-width: 18rem;">
            <div class="card-header">Класиране по брой опити:</div>
            <div class="card-body">
                <h5 class="card-title">Топ 10</h5>
                <ol class="card-text" id="top-tries-list"></ol>
            </div>
        </div>
        <div class="card text-dark bg-light mb-3" style="max-width: 18rem;">
            <div class="card-header">Класиране по време:</div>
            <div class="card-body">
                <h5 class="card-title">Топ 10</h5>
                <ol class="card-text" id="top-time-list"></ol>
            </div>
        </div>
    </div>
</main>
</body>
</html>
