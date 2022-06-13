@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')

@include('warehouse.nav')

@livewire('warehouse.categories.category-edit', [
    'store' => $store,
    'category' => $category
])

@endsection
