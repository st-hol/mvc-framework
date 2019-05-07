<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ >>> ^^($title)}}</title>
</head>
<body>
<h1>Привет, <strong style="color:red;"> {{ >>> ^^($uname)}} </strong>, рад тебя видеть.</h1>
    <br>
    <h4>В этих фразах начальная буква каждого слова соответствует начальной букве названия определённого цвета.</h4>
    {{ [[ ($rainbow as $color): >>> "\n <br> $color";   ]]  }}

    <br>
    <img src="{{ >>> ^^($img_url)}}">
    <p></p>
</body>
</html>