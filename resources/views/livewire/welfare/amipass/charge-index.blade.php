<div>
    <div class="row">
        <div class="group col-3">
            <label for="text11">Año</label>
            <select class="form-control" wire:model.live="year" id="">
                <option value=""></option>
                <option value="2023" >2023</option>
                <option value="2024" >2024</option>
                <option value="2025" >2025</option>
            </select>
        </div>
    </div><hr>

    @if($year == 2023)
        @if($records->count() > 0)
        <table class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <!-- <th width="95px" scope="col">Rut</th> -->
                    <!-- <th scope="col">Funcionario</th> -->
                    <th scope="col">Lugar desempeño</th>
                    <th scope="col">Fecha registro</th>
                    <th scope="col">Total real cargado</th>
                    <th scope="col">Días de ausentismos</th>
                    <th scope="col">Días hábiles del mes</th>
                    <th scope="col">Días a cargar</th>
                    <th scope="col">Valor día</th>
                    <th scope="col">Valor que debía cargarse</th>
                    <th scope="col">Diferencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr>
                    <!-- <td>{{ $record->rut }}-{{ $record->dv }}</td> -->
                    <!-- <td>{{ $record->nombre }}</td> -->
                    <td>{{ $record->lugar_desempeño }}</td>
                    <td>{{ Str::after($record->fecha, '-') }}</td>
                    <td class="text-right">{{ number_format($record->total_real_cargado, 0, ",", ".") ?? '-' }}</td>
                    <td class="text-right">{{ $record->dias_ausentismo ?? '-' }}</td>
                    <td class="text-right">{{ $record->dias_habiles_mes ?? '-' }}</td>
                    <td class="text-right">{{ $record->dias_a_cargar ?? '-' }}</td>
                    <td class="text-right">{{ number_format($record->valor_dia, 0, ",", ".") ?? '-' }}</td>
                    <td class="text-right">{{ number_format($record->valor_debia_cargarse, 0, ",", ".") ?? '-' }}</td>
                    <td class="{{$record->diferencia_color}} text-right">{{ number_format($record->diferencia, 0, ",", ".") ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-weight-bold text-right">
                    <td colspan="2" class="text-right">Totales $</td>
                    <td>{{number_format($records->sum('total_real_cargado'), 0, ",", ".")}}</td>
                    <td>{{$records->sum('dias_ausentismo')}}</td>
                    <td>{{$records->sum('dias_habiles_mes')}}</td>
                    <td>{{$records->sum('dias_a_cargar')}}</td>
                    <td></td>
                    <td>{{number_format($records->sum('valor_debia_cargarse'), 0, ",", ".")}}</td>
                    <td class="{{ $records->sum('diferencia') > 0 ? 'text-success' : 'text-danger' }}">{{number_format($records->sum('diferencia'), 0, ",", ".")}}</td>
                </tr>
            </tfoot>
        </table>

        <div class="alert alert-info" role="alert">
            A la fecha de Agosto-23 presentaba un saldo {{$records->sum('diferencia') > 0 ? 'a favor' : 'en contra' }} de ${{number_format(abs($records->sum('diferencia')), 0, ",", ".")}} entre todas las cargas autorizadas el cual será regularizada en la(s) próxima(s) carga(s) programada(s).
        </div>
        @else
        <fieldset>No presenta registros de cargas efectivas en Amipass</fieldset>
        @endif

        @if($regularizations->count() > 0)
        <table class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <th colspan="5">Regularizaciones registradas</th>
                </tr>
                <tr>
                    <!-- <th width="95px" scope="col">Rut</th> -->
                    <!-- <th scope="col">Funcionario</th> -->
                    <th scope="col">Lugar desempeño</th>
                    <th scope="col">Fecha registro</th>
                    <th scope="col">Total regularizado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regularizations as $record)
                <tr>
                    <!-- <td>{{ $record->rut }}-{{ $record->dv }}</td> -->
                    <!-- <td>{{ $record->nombre }}</td> -->
                    <td>{{ $record->lugar_desempeño }}</td>
                    <td>{{ Str::after($record->fecha, '-') }}</td>
                    <td class="{{ $record->total_real_cargado > 0 ? 'text-success' : 'text-danger' }} font-weight-bold text-right">{{ number_format($record->total_real_cargado, 0, ",", ".") ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <fieldset>No presenta registros de regularizaciones</fieldset>
        @endif

        @if($new_records->count() > 0)
        <table class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <th colspan="10">Mis cargas de Oct-23 a la fecha</th>
                </tr>
                <tr>
                    <!-- <th width="95px" scope="col">Rut</th> -->
                    <!-- <th scope="col">Funcionario</th> -->
                    <th scope="col">Lugar desempeño</th>
                    <th scope="col">Fecha registro</th>
                    <th scope="col">Días hábiles del mes</th>
                    <th scope="col">Días de ausentismos</th>
                    <th scope="col">Valor día</th>
                    <th scope="col">Subtotal mes</th>
                    <th scope="col">Valor regularizado</th>
                    <th scope="col">Valor a cargar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($new_records as $record)
                <tr>
                    <!-- <td>{{ $record->rut }}-{{ $record->dv }}</td> -->
                    <!-- <td>{{ $record->nombre }}</td> -->
                    <td>{{ $record->lugar_desempeño }}</td>
                    <td>{{ Str::after($record->fecha, '-') }}</td>
                    <td class="text-right">{{ $record->dias_habiles_mes ?? '-' }}</td>
                    <td class="text-right">{{ $record->dias_ausentismo ?? '-' }}</td>
                    <td class="text-right">{{ number_format($record->valor_dia, 0, ",", ".") ?? '-' }}</td>
                    <td class="text-right">{{ number_format($record->subtotal, 0, ",", ".") ?? '-' }}</td>
                    <td class="text-right">{{ number_format($record->total_regularizado, 0, ",", ".") ?? '-' }}</td>
                    <td class="text-right font-weight-bold">{{ number_format($record->valor_a_cargar, 0, ",", ".") ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <fieldset>No presenta registros de cargas efectivas en Amipass</fieldset>
        @endif

    @else

        <h4>Información histórica</h4>
        <table class="table table-sm table-bordered table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <!-- <th width="95px" scope="col">Rut</th>
                    <th scope="col">Funcionario</th>
                    <th scope="col">Lugar desempeño</th> -->
                    <th scope="col">Fecha registro</th>
                    <!-- <th scope="col">Total real cargado</th> -->
                    <th scope="col">Días hábiles del mes</th>
                    <th scope="col">Días de ausentismos</th>
                    <th scope="col">Días a cargar</th>
                    <th scope="col">Valor día</th>
                    <th scope="col">Valor a cargar</th>
                    <th scope="col">Valor cargado</th>
                    <th scope="col">Diferencia</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @if(is_countable($calculatedData) && count($calculatedData) > 0)
                    @foreach ($calculatedData as $ct => $user)
                        @if($user->shifts->count() == 0)
                            
                            <tr >
                                <td>{{$ct}}</td>
                                <td>{{$user->businessDays}}</td>
                                <td>{{$user->totalAbsenteeisms }}</td>
                                <td>{{$user->dailyAmmount}}</td>
                                <td>{{$user->businessDays - $user->totalAbsenteeisms}}</td>
                                <td>{{ money($user->ammount) }}</td>
                                <td>{{ money($user->AmiLoadMount) }}</td>
                                <td class="{{ ($user->ammount - $user->AmiLoadMount) > 0 ? 'text-success' : 'text-danger' }} font-weight-bold text-right">{{ money($user->ammount - $user->AmiLoadMount) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#demo{{$ct}}" aria-expanded="false" aria-controls="collapseExample">
                                        Detalles
                                    </button>
                                </td>
                            </tr>
                            
                            <tr class="collapse" id="demo{{$ct}}">
                                <!-- <td></td> -->
                                <td></td>
                                <td></td>
                                <td class="small">
                                    <ul>
                                        <!-- ausentismos (sin considerar dias compensatorios) -->
                                        @foreach($user->absenteeisms as $absenteeism)
                                        <li> 
                                            @if($absenteeism->totalDays==0)
                                                {{ $absenteeism->finicio->format('Y-m-d') }} - {{ $absenteeism->ftermino->format('Y-m-d') }} 
                                                <small>({{ $absenteeism->tipo_de_ausentismo }})</small> 
                                                Dias: {{ $absenteeism->total_dias_ausentismo }} => {{ $absenteeism->totalDays}}
                                            @else 
                                                <p style="color:red;display: inline;">
                                                    {{ $absenteeism->finicio->format('Y-m-d') }} - {{ $absenteeism->ftermino->format('Y-m-d') }} 
                                                    <small>({{ $absenteeism->tipo_de_ausentismo }})</small> 
                                                    Dias: {{ $absenteeism->total_dias_ausentismo }} => {{ $absenteeism->totalDays}}
                                                </p>
                                            @endif
                                        </li>
                                        @endforeach

                                        <!-- solo dias compensatorios -->
                                        @foreach($user->compensatoryDays as $compensatoryDay)
                                        <li> 
                                            @if($compensatoryDay->totalDays==0)
                                                {{ $compensatoryDay->start_date->format('Y-m-d H:i') }} - {{ $compensatoryDay->end_date->format('Y-m-d H:i')}} => {{$compensatoryDay->start_date->diffInHours($compensatoryDay->end_date)}} Hrs. 
                                                <small>(DÍA COMPENSATORIO)</small> 
                                                Dias: {{ $compensatoryDay->total_dias_ausentismo }} => {{ $compensatoryDay->totalDays}}
                                            @else 
                                                <p style="color:red;display: inline;">
                                                    {{ $compensatoryDay->start_date->format('Y-m-d H:i') }} - {{ $compensatoryDay->end_date->format('Y-m-d H:i') }} => {{$compensatoryDay->start_date->diffInHours($compensatoryDay->end_date)}} Hrs. 
                                                    <small>(DÍA COMPENSATORIO)</small> 
                                                    Dias: {{ $compensatoryDay->total_dias_ausentismo }} => {{ $compensatoryDay->totalDays}}
                                                </p>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <!-- <small>D.Ausentismo: {{$user->dias_ausentismo}}</small>  -->
                                </td>
                            </tr>
                        @else
                            <tr class="table-info">
                                <td>{{$ct}} (Turno)</td>
                                <td></td>
                                <td></td>
                                <td>{{ money($user->shiftAmmount) }}</td>
                                <td>{{ $user->shifts->sum('quantity') }}</td>
                                <td>{{ money($user->shiftAmmount * $user->shifts->sum('quantity')) }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#demo{{$ct}}" aria-expanded="false" aria-controls="collapseExample">
                                        Detalles
                                    </button>
                                </td>
                            </tr>

                            <tr class="collapse" id="demo{{$ct}}">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    @foreach($user->shifts as $shift)
                                        <ul>
                                            <li>{{$shift->year}} - {{$shift->month}} => {{$shift->quantity}}</li>
                                        </ul>
                                    @endforeach
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>

    @endif
    
</div>
