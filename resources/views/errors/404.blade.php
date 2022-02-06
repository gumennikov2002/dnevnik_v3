<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

        .title, .upper-title, .close {
            color: #2a3e52;
            font-family: 'Roboto-Bold';
        }

        .title {
            font-size: 120px;
        }

        .upper-title {
            font-size: 24px;
        }

        .under-title {
            color: #aab6c3;
            font-family: 'Roboto-Black';
        }


        body {
            background: linear-gradient(90deg, rgba(75, 64, 170, 1) 0%, rgba(195, 0, 255, 1) 100%);
        }

        .window {
            background: #fff;
            border-radius: 10px;
            height: auto;
            width: 800px;
            opacity: 0.9;
        }

        .window .row{
            padding: 80px;
        }

        .close {
            margin-top: 20px;
            margin-right: 10px;
        }

        @media screen and (max-width: 1200px) {
            .window {
                text-align: center;
            }
        }
    </style>

    <div class="container mt-5 mb-5 window">
        <div class="d-flex justify-content-end">
            <a href="/profile" class="close"><i class="fa fa-close"></i></a>
        </div>
        <div class="row align-items-center">
            <div class="col-md-1 col-xs-0"></div>
            <div class="col-md-5 col-xs-1">
                <img src="images/upset.png" ondragstart="return false;" width="256px">
            </div>
            <div class="col-md-6 col-xs-1">
                <span class="upper-title">Кажется вы потерялись</span> <br>
                <span class="title">404</span> <br>
                <span class="under-title">Страница, которую вы ищите недоступна</span> <br>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/6da500c7de.js" crossorigin="anonymous"></script>
</body>

</html>