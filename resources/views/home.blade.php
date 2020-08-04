@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="jumbotron mt-5">
    <div class="row">
    <div class="col-9">
        <h1 class="display-4">Intranet Online</h1>
        <p class="lead">{{ env('APP_SS') }}</p>
        <hr class="my-5">
        <p>Contacto:
            <a href="mailto:{{ env('APP_SS_EMAIL') }}">{{ env('APP_SS_EMAIL') }}</a>
        </p>

    </div>
    <div class="col-3">
        <img src="{{ asset('images/logo_blanco.png') }}"
            alt="Logo {{ env('APP_SS') }}"
            style="background-color: rgb(0, 108, 183);"
            class="img-thumbnail">
    </div>
</div>

<div class="alert alert-light" style="display: none" role="alert" id="developers">
    Hola {{ auth()->user()->name }} soy el sistema <i class="fas fa-cog fa-spin fa-2x" style="color:green"></i>
    , quiero contarte que fui desarrollado por <a href="mailto:alvaro.torres@redsalud.gob.cl">Alvaro Torres</a> y
    <a href="mailto:jorge.mirandal@redsalud.gob.cl">Jorge Miranda</a> del Servicio de Salud Iquique.

    <hr>

    <pre></pre>
</div>
@endsection
