<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestor de fotos</title>
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap-5.1.3-dist/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/star-rating.css')}}">
	<style type="text/css">
		form{
			border: solid darkgray 1px;
			border-radius: 20px;
			padding: 50px;
			margin: 30px;
		}
	</style>
</head>
<body>
	<div class="container">
	    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      		<a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
	      		@auth
	      			Las fotos de {{Auth::user()->name}}
	      		@endauth
	    	</a>
	    	<div class="col-md-3 text-end">
	      		@auth
		        	<a href="{{route('logout')}}"><button type="button" class="btn btn-outline-primary me-2">Salir</button></a>
	      		@else
		        	<a href="{{route('login')}}"><button type="button" class="btn btn-outline-primary me-2">Entrar</button></a>
		        	<a href="{{route('register')}}"><button type="button" class="btn btn-primary">Registrarse</button></a>
		    	@endauth
	        </div>
	    </header>
	</div>
	<article class="container">
		@if($errors->any() && $errors->getBag('default')->has('exception'))
			<div class="alert alert-danger" role="alert">
				{{$errors->getBag('default')->first('exception')}}
		  	</div>
		@endif
		@yield('content')
	</article>
	<script type="text/javascript" src="{{asset('bootstrap-5.1.3-dist/js/bootstrap.min.js')}}"></script>
	@yield('scripts')
</body>
</html>