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
            background: #fafafa;
        }

        img {
            width: 200px;
        }

    </style>

    <div class="container mt-5">
        <div class="d-flex-justify-content-center">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 d-flex justify-content-center flex-column text-center">
                    <center><img src="/images/logo-b.png"></center>
                    <h1 class="mt-5">404 | Страница не найдена</h1>
                    <p>
                        <a href="{{ url()->previous() }}">Назад</a>
                    </p>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
</body>

</html>