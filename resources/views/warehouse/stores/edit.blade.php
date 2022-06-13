@extends('layouts.app')

@section('title', 'Editar Bodega')

@section('content')

@include('warehouse.nav')

@livewire('warehouse.stores.store-edit', [
    'store' => $store
])

@endsection
