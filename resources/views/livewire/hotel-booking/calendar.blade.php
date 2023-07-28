<div>
    @if($start_date)   
        <div class="form-row mb-4 justify-content-end">
            <div style="color:LightSkyBlue">&#9632;</div>&nbsp;<p>Mis reserva</p>&nbsp;&nbsp;
            <div style="color:#F1948A">&#9632;</div>&nbsp;<p>No disponible</p>
            <div style="color:#FADBD8">&#9632;</div>&nbsp;<p>Pasados</p>
            <div style="color:#A3E4D7">&#9632;</div>&nbsp;<p>Disponible</p>
        </div>
    @endif
    
    <!-- Muestra como título el nombre de la OU y el selector de mes -->
    <div class="form-row mb-4">
        <div class="col-12 col-md-9">
            <!-- Muestra el nombre del mes seleccionado (Ej: Febrero 2023) -->
            <h5 clas="mb-3">
                {{ ucfirst($startOfMonth->monthName) }} de {{ $startOfMonth->year }}
            </h5>
        </div>
        <div class="col-6 col-md-3">
            <input class="form-control" type="month" wire:model="monthSelection">
        </div>
    </div>

    <!-- Mensaje de éxito
    @include('layouts.partials.flash_message') -->

    <!-- Rellena con cuadros en blanco para cuando el mes no comienza en el primer cuadro -->
    @for($i = 1; $i < $blankDays; $i++) 
        <div class="dia_calendario small p-2 text-center border-white"></div>
    @endfor

    <!-- @php
        var_dump($data);
    @endphp -->
    <!-- Muestra el calendario -->
    @foreach($data as $date => $item)
    <div class="dia_calendario small p-2 text-center {{ ($today == $date) ? 'border-primary' : '' }} {{$item['style']}}">

        <span class="{{ ($item['holiday'] OR $item['date']->dayOfWeek == 0) ? 'text-danger': '' }}">
                {{ $date }}
        </span>

        @if(!$start_date)
            <br>
            <span>
                @if($item['active']) <b>Activo</b> @else -- @endif
            </span>
        @endif

    </div>
    @endforeach

</div>