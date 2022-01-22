<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/app.css" rel="stylesheet">
    <title>@yield('title')</title>
</head>

<body>
    <div class="row" style="margin-right: 0">
        <div class="col-sm-1">
            @include('layout.sidebar')
        </div>
        <div class="col-sm-10 mt-2">
            <div class="container">
                <h2>@yield('page_title')</h2>
                @yield('content')
            </div>
        </div>

    </div>


    <script src="js/Controller.js"></script>
    <script src="https://kit.fontawesome.com/6da500c7de.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>