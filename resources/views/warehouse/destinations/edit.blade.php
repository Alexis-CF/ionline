@extends('layouts.app')

@section('title', 'Editar Destino')

@section('content')

@include('warehouse.nav')

@livewire('warehouse.destinations.destination-edit', [
    'store' => $store,
    'destination' => $destination
])

@endsection
