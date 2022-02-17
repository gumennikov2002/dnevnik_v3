<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/app.css">
    <title>Страница не найдена</title>
</head>

<body>
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url('fonts/roboto/Roboto-Regular.ttf');
        }

        @font-face {
            font-family: 'Roboto-Bold';
            src: url('fonts/roboto/Roboto-Bold.ttf');
        }

        @font-face {
            font-family: 'Roboto-Black';
            src: url('fonts/roboto/Roboto-Black.ttf');
        }

        * {
            font-family: 'Roboto';
        }

        body {
            background: #948dcc;
        }

        img {
            width: 30%;
        }

    </style>

    <center>
        <div style="
            z-index: 999;
            position: absolute;
            margin: auto;
            top: 58%;
            left: 17%;
            bottom: 0;
            right: 0;
        ">
            <h4 class="text-light">Страница не найдена</h4>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Назад</a>
        </div>
        <img src="/images/404.png" style="
            position: absolute;
            margin: auto;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;"
        ondragstart="return false;">
    </center>
</body>

</html>