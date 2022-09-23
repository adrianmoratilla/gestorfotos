<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestor de fotos</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-5.1.3-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/star-rating.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <style type="text/css">
        form {
            border: solid darkgray 1px;
            border-radius: 20px;
            padding: 50px;
            margin: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header
            class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none" id="headerTitle">
                @auth
                    <h1>Fotos de {{ ucwords(Auth::user()->name) }}</h1>
                @endauth
            </a>
            <div class="col-md-3 text-end">
                @auth
                <div class="input-group" style="margin-right: 5%">
                    <input type="text" class="form-control" autocomplete="off" id="searchBox" placeholder="Buscar fotos..." onkeyup="filterPictures(event.target)" style="display:none">
                    <div class="input-group-append" onclick="showSearch()">
                        <span class="input-group-text" title="Buscar fotos por título" style="cursor: pointer; background-color:#DFEAD7; border-color: #d2e2c6"><i class="bi bi-search"></i></span>
                    </div>
                </div>
                    <a href="{{ route('logout') }}"><button type="button"
                            class="btn btn-outline-primary me-2" title="Cerrar sesión"><i class="bi bi-box-arrow-right"></i></button></a>
                @else
                    <a href="{{ route('login') }}"><button type="button"
                            class="btn btn-outline-primary me-2">Entrar</button></a>
                    <a href="{{ route('register') }}"><button type="button"
                            class="btn btn-primary">Registrarse</button></a>
                @endauth
            </div>
        </header>
    </div>
    <article class="container">
        @if ($errors->any() && $errors->getBag('default')->has('exception'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->getBag('default')->first('exception') }}
            </div>
        @endif
        @yield('content')
    </article>
    <script type="text/javascript" src="{{ asset('bootstrap-5.1.3-dist/js/bootstrap.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
