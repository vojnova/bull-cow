<!doctype html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Бикове и крави</title>
    <script type="text/javascript" src="/js/game.js"></script>
{{--    <script src="{{asset('public/js/app.js')}}"></script>--}}
</head>
<body>
<div id="enter-name">
    <h1>Добре дошли!</h1>
    <h3>Моля, въведете своето име, за да започнете.</h3>
    <form onsubmit="event.preventDefault(); newGame()">
    <input type="text" id="name" minlength="3" required>
    <button type="submit">Нова игра</button>
    </form>
</div>
<div id="play" style="display: none">
    <h3>Опитайте се да познаете числото, като въведете 4 уникални цифри:</h3>
    <form onsubmit="event.preventDefault(); guessNumber()">
        <input type="text" maxlength="4" pattern="[0-9]{4}" id="guess">
        <button type="submit">Познай</button>
        <div id="results"></div>
    </form>
</div>
</body>
</html>
