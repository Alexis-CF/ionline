@extends('layouts.app')

@section('title', 'Editar Unidad Organizacional')

@section('content')

<h3>Editar Unidad Organizacional del {{Auth::user()->organizationalUnit->establishment->name}}</h3>

<form method="POST" class="form-horizontal" action="{{ route('rrhh.organizational-units.update',$organizationalUnit->id) }}">
	{{ method_field('PUT') }} {{ csrf_field() }}

	<div class="row">
		<fieldset class="form-group col-4">
			<label for="forEstablishment">Id Establecimiento</label>
			<input type="text" class="form-control" id="forEstablishment"
				name="establishment_id" required="required" value="{{ $organizationalUnit->establishment_id }}" readonly>
		</fieldset>
	</div>

	@cannot(['Service Request', 'Service Request: export sirh mantenedores'])
	<div class="row">
		<fieldset class="form-group col-12">
			<label for="forName">Nombre</label>
			<input type="text" class="form-control" id="forName" name="name" value="{{ $organizationalUnit->name }}">
		</fieldset>
	</div>

	<div class="row">
		<fieldset class="form-group col-9">
			<label for="forFather">Depende de</label>
			<select class="custom-select" id="forFather" name="father">
				@foreach($organizationalUnits as $ou)
					<option value="{{ $ou->id }}" @if ($organizationalUnit->father == $ou) selected="selected" @endif>{{ $ou->name }}</option>
				@endforeach
			</select>
		</fieldset>

		<fieldset class="form-group col-3">
			<label for="forLevel">Nivel</label>
			<input type="number" class="form-control" id="forLevel"
				name="level" required="required" value="{{ $organizationalUnit->level }}">
		</fieldset>
	</div>
	@else
		<div class="row">
			<fieldset class="form-group col-12">
				<label for="forName">Nombre</label>
				<input type="text" class="form-control" id="forName" name="name" value="{{ $organizationalUnit->name }}" readonly>
			</fieldset>
		</div>

		<div class="row">
			<fieldset class="form-group col-9">
				<label for="forFather">Depende de</label>
				<select class="custom-select" disabled>
					@foreach($organizationalUnits as $ou)
						<option value="{{ $ou->id }}" @if ($organizationalUnit->father == $ou) selected="selected" @endif>{{ $ou->name }}</option>
					@endforeach
				</select>
			</fieldset>

			<input type="hidden" class="form-control" id="forfather" name="father" value="{{ $organizationalUnit->father->id }}">

			<fieldset class="form-group col-3">
				<label for="forLevel">Nivel</label>
				<input type="number" class="form-control" id="forLevel"
					name="level" required="required" value="{{ $organizationalUnit->level }}" readonly>
			</fieldset>
		</div>
	@endcan

	<div class="row">
		<fieldset class="form-group col-6">
			<label for="forName">Id Función (SIRH)</label>
			<input type="text" class="form-control" id="forsirh_function"
				name="sirh_function" required="required" value="{{$organizationalUnit->sirh_function}}">
		</fieldset>

		<fieldset class="form-group col-6">
			<label for="forName">Id Unid.Org. (SIRH)</label>
			<input type="text" class="form-control" id="forsirh_ou_id"
				name="sirh_ou_id" required="required" value="{{$organizationalUnit->sirh_ou_id}}">
		</fieldset>
	</div>

	<div class="row">
		<fieldset class="form-group col-12">
			<button type="submit" class="btn btn-primary">
				<span class="fas fa-save" aria-hidden="true"></span> Actualizar</button>

			</form>

			<a href="{{ route('rrhh.organizational-units.index') }}" class="btn btn-outline-dark">Cancelar</a>

			@cannot(['Service Request', 'Service Request: export sirh mantenedores'])
			<form method="POST" action="{{ route('rrhh.organizational-units.destroy', $organizationalUnit->id) }}" class="d-inline">
				{{ method_field('DELETE') }} {{ csrf_field() }}
				<button class="btn btn-danger"><span class="fas fa-trash" aria-hidden="true"></span> Eliminar</button>
			</form>
			@endcan

		</fieldset>
	</div>

	@can('be god')
    <br /><hr />
    <div style="height: 300px; overflow-y: scroll;">
        @include('partials.audit', ['audits' => $organizationalUnit->audits] )
    </div>
	@endcan

@endsection
