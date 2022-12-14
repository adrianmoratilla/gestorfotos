@extends('layout')

@section('content')
    <main class="login-form">
        <div class="justify-content-center">
            @if (Session::has('message'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('message') }}
                </div>
            @endif
            <form action="{{ route('forget.password.post') }}" method="POST">
                <h4 class="mb-5" style="text-align: center">¿Has olvidado tu contraseña?</h4>
                @csrf
                <div class="form-group row">
                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Dirección e-mail</label>
                    <div class="col-md-6 mb-3">
                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Restablecer contraseña
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
