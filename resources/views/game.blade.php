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
    <link rel="stylesheet" href="{{asset('/css/game.css')}}">
    <script type="text/javascript" src="{{asset('/js/game.js')}}"></script>
</head>
<body onload="getTop('last')">
<nav class="navbar navbar-light bg-light">
    <div class="container-fluid justify-content-between">
        <span class="navbar-brand">
            <a href="{{url('/game')}}"><img src="{{asset('bullcowkiss.gif')}}" width="100px"></a>
            <span>Бикове и крави</span>
        </span>
        <button class="btn btn-outline-primary me-2" type="button" onclick="newGame()">Нова игра</button>
        <button
            id="popover"
            type="button"
            class="btn btn-outline-secondary me-2"
            data-mdb-toggle="popover"
            title="Как се играе?"
            data-mdb-content="Бикове и крави е традиционна игра, в която трябва да бъде позната намислена комбинация от цифри.
            Програмата генерира комбинация от 4 уникални цифри (една и съща цифра не може да се среща повече от веднъж, комбинацията може да започва с 0). След като започнете играта, трябва да въведете своето предположение и ще получите резултат под формата на брой бикове и брой крави.
            Един бик означава, че е позната една цифра и тя е поставена на точното си място; Крава обозначава, че цифрата присъства в комбинацията, но не е поставена на правилното място.
            Играта приключва, когато познаете точно генерираната комбинация, което се равнява на 4 бика.
            Ако решите да се откажете, можете да изберете Предавам се, при което ще видите коя е била генерираната комбинация.
            Приятна игра!"
        >
            Правила
        </button>
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
            <h3 class="card-title">Опитайте се да познаете комбинацията, като въведете 4 различни цифри:</h3>
            <form class="input-group mb-3" onsubmit="event.preventDefault(); guessNumber()" id="play-form">
                <input class="form-control" type="text" maxlength="4" pattern="^(?:([0-9])(?!.*\1)){4}$" id="guess" required autocomplete="off">
                <button class="btn btn-success" type="submit">Познай</button>
                <button class="btn btn-danger" type="button" onclick="giveUp()">Предавам се</button>
            </form>
            <div id="results" class="card-text"></div>
        </div>
    </div>
    <div class="card text-dark bg-light mb-3">
        <div class="card-body">
            <h5 class="card-title">Топ 10</h5>
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <a class="nav-link" id="top-tries-link" href="#" onclick="changeTab('tries')">по брой опити</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" id="top-times-link" onclick="changeTab('times')">по време</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#" id="top-last-link" onclick="changeTab('last')">най-скорошни</a>
                </li>
            </ul>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Име</th>
                    <th scope="col">Опити</th>
                    <th scope="col">Време</th>
                </tr>
                </thead>
                <tbody id="top-tbody">
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    const popover = new mdb.Popover(document.getElementById('popover'));
</script>
</body>
</html>
