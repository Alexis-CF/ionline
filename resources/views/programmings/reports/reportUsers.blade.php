@extends('layouts.app')

@section('title', 'Lista de Usuarios')

@section('content')

@include('programmings/nav')


<button onclick="tableExcel('xlsx')" class="btn btn-success float-right btn-sm">Exportar Excel</button>

<h4 class="mb-3"> Reporte de usuarios</h4>
<form method="GET" class="form-horizontal small" action="{{ route('programming.reportUsers') }}" enctype="multipart/form-data">

    <div class="form-row">
        <div class="form-group col-md-2">
            <select name="role" id="role" placeholder="Rol" class="form-control" required>
                <option value="Review" {{ $role == 'Review' ? 'selected' : ''}}>Revisor</option>
                <option value="Basic" {{ $role == 'Basic' ? 'selected' : ''}}>Establecimiento</option>
                <option value="Comunal" {{ $role == 'Comunal' ? 'selected' : ''}}>Comunal</option>
                <option value="Training" {{ $role == 'Training' ? 'selected' : ''}}>Capacitacion</option>
                <option value="Admin" {{ $role == 'Admin' ? 'selected' : ''}}>Administrativo</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <select name="commune" id="commune" class="form-control">
                <option value="">Comuna</option>
                <option value="7" {{ $commune == 7 ? 'selected' : ''}}>Alto Hospicio</option>
                <option value="6" {{ $commune == 6 ? 'selected' : ''}}>Iquique</option>
                <option value="5" {{ $commune == 5 ? 'selected' : ''}}>Pica</option>
                <option value="2" {{ $commune == 2 ? 'selected' : ''}}>Huara</option>
                <option value="3" {{ $commune == 3 ? 'selected' : ''}}>Camiña</option>
                <option value="4" {{ $commune == 4 ? 'selected' : ''}}>Pozo Almonte</option>
                <option value="1" {{ $commune == 1 ? 'selected' : ''}}>Colchane</option>
            </select>
        </div>

        <button type="submit" class="btn btn-info mb-4">Generar</button>
    </div>
</form>


@if($users->count() > 0)
<table id="tblData" class="table table-striped  table-sm table-bordered table-condensed fixed_headers table-hover  ">
    <thead>
        <tr class="small ">
            <th>#</th>
            <th class="text-center align-middle">Perfil</th>
            <th class="text-center align-middle">Nombre funcionario</th>
            <th class="text-center align-middle">Establecimiento(s)</th>
        </tr>
    </thead>
    <tbody >
        @foreach($users as $user)
        <tr class="small">
            <td>{{$loop->iteration}}</td>
            <td class="text-center align-middle font-italic">{{ $role }}</td>
            <td class="text-center align-middle">{{ $user->fullName }}</td>
            <td class="text-center align-middled">{{ $user->accessByEstablishments->count() > 0 ? $user->accessByEstablishments->implode(', ') : 'SS Tarapacá' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<fieldset>No presenta registros de usuarios con perfil {{$role}}</fieldset>
@endif

@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>


<script type="text/javascript">
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });
</script>

<script>
    function tableExcel(type, fn, dl) {
        var elt = document.getElementById('tblData');
        const filename = 'Informe_consolidado'
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "Sheet JS",
            raw: true
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, `${filename}.xlsx`)
    }
</script>
@endsection