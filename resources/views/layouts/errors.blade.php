<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    {!! HTML::style('assets/css/app.css') !!}

    <style>
        body { padding-top: 100px; background-color: #fff; }
        h1 { font-size: 50px; }

        @media (max-width: 768px) {
            body { padding-top: 50px; }
            h1 { font-size: 36px; }
            img { width: 100px; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-7 m-auto">
                <div class="text-center">
                    <img src="{{ url('assets/img/vanguard-logo-no-text.png') }}" alt="Vanguard">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>