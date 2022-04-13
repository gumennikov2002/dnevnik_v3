<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/fav.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
    <link href="css/app.css" rel="stylesheet">
    <title>@yield('title')</title>
</head>

<body class="{{ $theme === 'dark' ? 'darkTheme' : '' }}">

    <div id="preloader" class="d-flex justify-content-center" style="background: {{ $theme === 'dark' ? '#1e1e1e' : '#f3f3f3' }}">
        <div class="d-flex justify-content-between">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
        </div>
    </div>
    @csrf

    @if($_SERVER['REQUEST_URI'] !== '/auth')
    <div class="row" style="margin-right: 0">
        <div class="col-sm-1">
            @include('layout.sidebar')
        </div>
        <div class="col-sm-10 mt-2">
            <div class="container">
                <h2 class="mt-2 z-top">@yield('page_title')</h2>
                <span class="mt-2 z-top">@yield('page_subtitle')</span>
            </div>

            <div class="spacer"></div>
            <div class="container pb-5">
                @yield('content')
            </div>
        </div>
    </div>
    @endif

    @if($_SERVER['REQUEST_URI'] === '/auth')
    @yield('content')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="js/Controller.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.25.0/axios.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    @yield('scripts')
</body>

</html>