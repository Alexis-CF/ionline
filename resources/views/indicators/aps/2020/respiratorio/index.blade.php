@extends('layouts.bt4.app')

@section('title', 'Programa respiratorio')

@section('content')

@include('indicators.partials.nav')

<h3 class="mb-3">Indicadores</h3>
<h6 class="mb-3">Programa Respiratorio.</h6>

<ol>
    <li> <a href="{{ route('indicators.aps.2020.respiratorio.aps') }}">APS.</a><span class="badge badge-warning"><i class="fas fa-exclamation"></i></span></li>
    <li> <a href="{{ route('indicators.aps.2020.respiratorio.reyno') }}">CGU Dr. Héctor Reyno</a> <span class="badge badge-warning"><i class="fas fa-exclamation"></i></span></li>
</ol>


@endsection

@section('custom_js')


@endsection

@section('custom_js_head')

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

@endsection
