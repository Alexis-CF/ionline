@extends('layouts.app')

@section('title', 'Lista de Items')

@section('content')

@include('programmings/nav')


<button onclick="tableExcel('xlsx')" class="btn btn-success float-right btn-sm">Exportar Excel</button>

<h4 class="mb-3"> Informe Consolidado año {{$year}}</h4>
<form method="GET" class="form-horizontal small" action="{{ route('programming.reportConsolidated') }}" enctype="multipart/form-data">

    <div class="form-row">
        <div class="form-group col-md-2">
            <select name="isTracer" id="isTracer" placeholder="Es trazadora" class="form-control" required>
                     <option value="SI" {{ $isTracer == 'SI' ? 'selected' : ''}}>Trazadoras</option>
                     <option value="NO" {{ $isTracer == 'NO' ? 'selected' : ''}}>NO trazadoras</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <select style="font-size:70%;" name="commune_filter" id="activity_search_id" placeholder="Tipo de Informe" class="form-control selectpicker"  required>
                     <option style="font-size:70%;" value="hospicio" {{ $option == 'hospicio' ? 'selected' : ''}}>Alto Hospicio</option>
                     <option style="font-size:70%;" value="iquique" {{ $option == 'iquique' ? 'selected' : ''}}>Iquique</option>
                     <option style="font-size:70%;" value="pica" {{ $option == 'pica' ? 'selected' : ''}}>Pica</option>
                     <option style="font-size:70%;" value="huara" {{ $option == 'huara' ? 'selected' : ''}}>Huara</option>
                     <option style="font-size:70%;" value="camiña" {{ $option == 'camiña' ? 'selected' : ''}}>Camiña</option>
                     <option style="font-size:70%;" value="pozoalmonte" {{ $option == 'pozoalmonte' ? 'selected' : ''}}>Pozo Almonte</option>
                     <option style="font-size:70%;" value="colchane" {{ $option == 'colchane' ? 'selected' : ''}}>Colchane</option>
                     <option style="font-size:70%;" value="hectorreyno" {{ $option == 'hectorreyno' ? 'selected' : ''}}>Hector Reyno</option>
            </select>
        </div>
        <fieldset class="form-group col-2">
            <input type="text" class="form-control " id="datepicker" name="year" placeholder="Año" required="" value="{{$year}}">
        </fieldset>
        <button type="submit" class="btn btn-info mb-4">Generar</button>
    </div>
</form>



<table id="tblData" class="table table-striped  table-sm table-bordered table-condensed fixed_headers table-hover  ">
    <thead>
        <tr style="font-size:75%;">
            <th class="text-center align-middle" colspan="5">INFORME CONSOLIDADO - {{strtoupper(Request::get('commune_filter')) ?? '' }}</th>
        </tr>
        <tr class="small " style="font-size:60%;">
            @if($isTracer == 'SI')<th class="text-center align-middle">Nº TRAZADORA</th>@endif
            <th class="text-center align-middle">PRESTACION O ACTIVIDAD</th>
            <th class="text-center align-middle">ACCIÓN</th>
            <th class="text-center align-middle">TOTAL ACTIVIDADES</th>
            <th class="text-center align-middle">ESTABLECIMIENTOS</th>
        </tr>
    </thead>
    <tbody style="font-size:70%;">
        @foreach($programmingItems as $programmingitem)
        <tr class="small">
        @if($isTracer == 'SI')<td class="text-center align-middle">{{ $programmingitem->int_code }}</td>@endif
            <td class="text-center align-middle">{{ $programmingitem->activity_name }}</td>
            <td class="text-center align-middle">{{ $programmingitem->action_type }}</td>
            <td class="text-center align-middle font-weight-bold">{{ number_format($programmingitem->activity_total,0, ',', '.') }}</td>
            <td class="text-left align-middle">{{ $programmingitem->establishments }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-size:60%;">
            <td class="text-center" colspan="{{$isTracer == 'SI' ? 3 : 2}}">TOTALES</td>
            <td class="text-center">{{ $programmingItems ? number_format($programmingItems->sum('activity_total'),0, ',', '.') : '0'}}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

@endsection

@section('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

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
          var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
          return dl ?
            XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
            XLSX.writeFile(wb, `${filename}.xlsx`)
        }
</script>
@endsection
