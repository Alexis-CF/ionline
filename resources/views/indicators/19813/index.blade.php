@extends('layouts.app')

@section('title', 'Ley 19.813')

@section('content')

@include('indicators.partials.nav')

<div class="col-5">
  <div class="card">
      <div class="card-header">
          <strong>Metas Sanitarias Ley N° 19.813</strong>
      </div>
      <ul class="list-group list-group-flush">
          <li class="list-group-item">
              <a href="{{ route('indicators.19813.2020.index') }}">2020</a> <span class="badge badge-warning">En Revisión</span>
          </li>
          <li class="list-group-item">
              <a href="{{ route('indicators.19813.2019.index') }}">2019</a>
          </li>
          <li class="list-group-item">
              <a href="{{ route('indicators.19813.2018.index') }}">2018</a>
          </li>
      </ul>
  </div>

</div>

@endsection

@section('custom_js')

@endsection
