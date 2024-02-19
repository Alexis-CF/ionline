@extends('layouts.bt5.app')

@section('title', 'Asignar roles a Usuario')

@section('content')

@include('rrhh.submenu')

@can('Users: assign permission')

<h3 class="mb-3">Asignar permisos y roles a: <strong> {{ $user->name }} </strong> ({{ $user->runFormat() }})</h3>

<form class="form-horizontal" method="POST" action="{{ route('rrhh.roles.attach',$user->id) }}">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">

    <div class="row">

        <div class="col-12 col-md-5">

            <h4>Permisos</h4>

            @can('be god')
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]"
                        value="be god" id="be god"
                        {{ $user->can('be god')? 'checked':'' }}>
                    <label class="form-check-label" for="be god">be god</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]"
                        value="dev" id="dev"
                        {{ $user->can('dev')? 'checked':'' }}>
                <label class="form-check-label" for="dev">dev</label>
                </div>
            @endcan

            @php $anterior = null; @endphp
            @foreach($permissions as $permission)
                @if($permission->name != 'be god' AND $permission->name != 'dev')
                    @if( current(explode(':', $permission->name)) != current(explode(':', $anterior)))
                        <hr>
                        @php $anterior = $permission->name; @endphp
                    @endif
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]"
                            value="{{ $permission->name }}" id="{{ $permission->name }}"
                            {{ $user->can($permission->name)? 'checked':'' }}>
                        <label class="form-check-label" for="{{ $permission->name }}">{{$permission->name}}</label>
                        <small class="form-text text-muted">{{ $permission->description }}</small>
                    </div>
                @endif
            @endforeach
            <input type="submit" class="btn btn-primary mt-5" value="Guardar">
        </div>

        <div class="col-2">
        </div>

        <div class="col-12 col-md-5">

            <h4>Roles</h4>

            @can('be god')
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                    value="god" id="god" name="roles[]"
                    {{ $user->can('be god') ? 'checked':'' }}>
                <label class="form-check-label" for="god">god</label>
            </div>
            <hr>
            @endcan

            @foreach($roles as $rol)
                @if($rol->name != 'god')
                    <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                            value="{{ $rol->name }}" id="{{$rol->name}}" name="roles[]"
                            {{ $user->hasRole($rol->name) ? 'checked':'' }} >
                          <label class="form-check-label" for="{{$rol->name}}">
                            {{$rol->name}}
                            <ul>
                            @foreach($rol->permissions as $permission)
                                <li class="small">{{ $permission->name }}</li>
                            @endforeach
                            </ul>
                        </label>
                    </div>
                @endif
            @endforeach
        </div>

    </div>

</form>

@endcan

@endsection
