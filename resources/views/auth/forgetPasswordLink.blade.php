@extends('layout')

@section('content')
    <main class="login-form">
        <div class="justify-content-center">
            <form action="{{ route('reset.password.post') }}" method="POST">
                <h4 class="mb-5">Cambia tu contraseña</h4>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group row mb-2">
                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail</label>
                    <div class="col-md-6">
                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="password" class="col-md-4 col-form-label text-md-right">Nueva contraseña</label>
                    <div class="col-md-6">
                        <input type="password" id="password" class="form-control" name="password" required autofocus>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirmar contraseña</label>
                    <div class="col-md-6 mb-3">
                        <input type="password" id="password-confirm" class="form-control" name="password_confirmation"
                            required autofocus>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Cambiar contraseña
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
