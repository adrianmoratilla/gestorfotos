@php

  $rutaSubmit = "";
  $titulo = "";
  
  if($tipo=='registro'){
    $titulo = 'Registrarse';
    $rutaSubmit = route('do-register');
  }else{
    $titulo = 'Iniciar sesión';
    $rutaSubmit = route('do-login');
  }

@endphp
<form method="POST" action="{{$rutaSubmit}}">
  @csrf
  <h1>{{$titulo}}</h1>
  @if($tipo=='registro')
    <div class="mb-3">
      <label for="inputEmail" class="form-label">Email</label>
      <input type="email" name="email" required  id="inputEmail" @class([
                "form-control",
                "is-valid" => $errors->any() && !$errors->getBag('default')->has('email'),
                "is-invalid" => $errors->any() && $errors->getBag('default')->has('email')
              ])
        value="{{ old('email') }}"
      >
      @if($errors->any() && $errors->getBag('default')->has('email'))
        <div class="invalid-feedback">
          @foreach($errors->getBag('default')->get('email') as $error)
            <p>{{$error}}</p>
          @endforeach
        </div>
      @endif
    </div>
  @endif
  <div class="mb-3">
    <label for="inputName" class="form-label">Nombre de usuario</label>
    <input type="text" maxlength="30" id="inputName" name="name" required aria-describedby="nameHelp" @class([
                "form-control",
                "is-valid" => $errors->any() && !$errors->getBag('default')->has('name'),
                "is-invalid" => $errors->any() && $errors->getBag('default')->has('name')
              ])
      value="{{ old('name') }}"
    >
    @if($tipo=='registro')
      <div id="nameHelp" class="form-text">Tu nombre para acceder a la aplicación. Debe ser único.</div>
    @endif
    @if($errors->any() && $errors->getBag('default')->has('name'))
      <div class="invalid-feedback">
        @foreach($errors->getBag('default')->get('name') as $error)
          <p>{{$error}}</p>
        @endforeach
      </div>
    @endif
  </div>
  <div class="mb-3">
    <label for="inputPassword" class="form-label">Password</label>
    <input type="password" name="password" id="inputPassword" required aria-describedby="passwordHelp" class="form-control">
    @if($tipo=='registro')
      <div id="passwordHelp" class="form-text">Debe tener al menos 6 caracteres con letras mayúsculas, minúsculas y números.</div>
    @endif
    @if($errors->any() && $errors->getBag('default')->has('password'))
      <div class="invalid-feedback">
        @foreach($errors->getBag('default')->get('password') as $error)
          <p>{{$error}}</p>
        @endforeach
      </div>
    @endif
  </div>
  @if($tipo=='login')
    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="checkRemember">
      <label class="form-check-label" for="checkRemember" name="remember">Mantener la sesión iniciada</label>
    </div>
  @endif
  <button type="submit" class="btn btn-primary">Enviar</button>
</form>