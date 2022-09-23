@extends('layout')
@section('content')
	<x-form tipoform='login'/>
@endsection
@section('scripts')
<script>
document.getElementById('inputPassword').classList.remove('is-valid');
</script>
@endsection
