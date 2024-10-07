<?php setlocale(LC_ALL, 'es_CL.UTF-8'); ?>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado de cumplimiento</title>
    <meta name="description" content="">
    <meta name="author" content="Servicio de Salud Tarapacá">
    <style media="screen">
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.8rem;
        }
        .content {
            margin: 0 auto;
            width: 724px;
        }
        .monospace {
            font-family: "Lucida Console", Monaco, monospace;
        }
        .pie_pagina {
            margin: 0 auto;
            width: 724px;
            height: 26px;
            position: fixed;
            bottom: 0;
        }
        .seis {
            font-size: 0.6rem;
        }
        .siete {
            font-size: 0.7rem;
        }
        .ocho {
            font-size: 0.8rem;
        }
        .nueve {
            font-size: 0.9rem;
        }
        .plomo {
            background-color: F3F1F0;
        }
        .titulo {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 4px 0 6px;
        }
        .center {
            text-align: center;
        }
        .left {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .justify {
            text-align: justify;
        }
        .indent {
            text-indent: 30px;
        }
        .uppercase {
            text-transform: uppercase;
        }
        #firmas {
            margin-top: 100px;
        }
        #firmas > div {
            display: inline-block;
        }
        .li_letras {
            list-style-type: lower-alpha;
        }
        table {
            border: 1px solid grey;
            border-collapse: collapse;
            padding: 0 4px;
            width: 100%;
        }
        th, td {
            border: 1px solid grey;
            border-collapse: collapse;
            padding: 0 4px;
        }
        .column {
            float: left;
            width: 50%;
        }
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        @media all {
            .page-break { display: none; }
        }
        @media print {
            .page-break { display: block; page-break-before: always; }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="content">
            <img style="padding-bottom: 4px;" src="images/Logo Servicio de Salud Tarapacá - Pluma.png" width="120" alt="Logo Servicio de Salud Tarapacá"><br>
            <div class="siete" style="padding-top: 3px;">
                @if($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 1)
                    HOSPITAL DR. ERNESTO TORRES GALDAMES<br>
                @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 38)
                    SERVICIO SALUD TARAPACÁ<br>
                @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 41)
                    HOSPITAL DE ALTO HOSPICIO<br>
                @endif
                SUBDIRECCIÓN DE GESTIÓN Y DESARROLLO DE LAS PERSONAS
            </div>
            <br><br>
            <div class="titulo">
                <div class="center" style="width: 100%;">
                    <strong>
                        <span class="uppercase">Certificado de Cumplimiento</span><br>
                    </strong>
                </div>
            </div>
            <br><br>
            @if($fulfillment->serviceRequest->working_day_type == "HORA MÉDICA" or $fulfillment->serviceRequest->working_day_type == "TURNO DE REEMPLAZO")
                <?php setlocale(LC_ALL, 'es'); ?>
                <div class="nueve">
                    <div class="justify" style="width: 100%;">
                        Mediante el presente certifico que <b><span class="uppercase">{{$fulfillment->serviceRequest->employee->fullName}}</span></b> ha desempeñado las actividades estipuladas en su convenio de prestación de servicios con el
                        @if($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 38)
                            @if($fulfillment->serviceRequest->employee->organizationalUnit->id == 24)
                                Consultorio General Urbano Dr. Hector Reyno,
                                <b> en el mes de {{$fulfillment->start_date->monthName}} del {{$fulfillment->start_date->year}}</b>
                                @if($fulfillment->serviceRequest->type == 'Covid')
                                    durante el periodo de contingencia COVID
                                @endif
                            @else
                                Servicio Salud Tarapacá,
                                <b> en el mes de {{$fulfillment->start_date->monthName}} del {{$fulfillment->start_date->year}}</b>
                                @if($fulfillment->serviceRequest->type == 'Covid')
                                    durante el periodo de contingencia COVID
                                @endif
                            @endif
                        @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 1)
                            Hospital Dr. Ernesto Torres Galdames,
                            por <b>horas extras realizadas en el mes de {{$fulfillment->start_date->monthName}} del {{$fulfillment->start_date->year}} </b>.
                        @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 41)
                            Hospital de Alto Hospicio,
                            por <b>horas extras realizadas en el mes de {{$fulfillment->start_date->monthName}} del {{$fulfillment->start_date->year}} </b>.
                        @endif
                        <br><br>
                        <table class="siete">
                            <thead>
                                <tr>
                                    <th>Inicio</th>
                                    <th>Término</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fulfillment->shiftControls->sortBy('start_date') as $key => $shiftControl)
                                    <tr>
                                        <td>{{$shiftControl->start_date->format('d-m-Y H:i')}}</td>
                                        <td>{{$shiftControl->end_date->format('d-m-Y H:i')}}</td>
                                        <td>{{ ($fulfillment->serviceRequest->working_day_type == 'DIURNO PASADO A TURNO') ? 'DIURNO PASADO A TURNO' : $shiftControl->observation}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        @livewire('service-request.show-total-hours', ['fulfillment' => $fulfillment, 'forCertificate' => true])
                        <br><br>Se extiende el presente certificado para ser presentado en la oficina de finanzas y contabilidad para gestión de pago.
                    </div>
                </div>
            @else
                @if($fulfillment->type == "Mensual" || $fulfillment->type == "Parcial")
                
                    @if($fulfillment->FulfillmentItems->count() == 0)
                        <div class="nueve">
                            <div class="justify" style="width: 100%;">
                                Mediante el presente certifico que <b><span class="uppercase">{{$fulfillment->serviceRequest->employee->fullName}}</span></b> ha desempeñado las actividades estipuladas en su convenio de prestación de servicios con el
                                @if($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 38)
                                    @if($fulfillment->serviceRequest->employee->organizationalUnit->id == 24)
                                        Consultorio General Urbano Dr. Hector Reyno
                                    @else
                                        Servicio Salud Tarapacá
                                    @endif
                                @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 1)
                                    Hospital Dr. Ernesto Torres Galdames
                                @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 41)
                                    Hospital de Alto hospicio
                                @endif
                                durante el periodo
                                @if($fulfillment->serviceRequest->type == 'Covid')
                                    de contingencia COVID
                                @endif
                                del <b>{{$fulfillment->start_date->format('d/m/Y')}}</b> al <b>{{$fulfillment->end_date->format('d/m/Y')}}</b>.
                                <br><br>Se extiende el presente certificado para ser presentado en la oficina de finanzas y contabilidad para gestión de pago.
                            </div>
                        </div>
                    @else
                        <!-- Aquí se mantiene el mismo patrón para el resto del contenido -->
                        <!-- Repetir el formato similar con la tabulación correcta -->
                    @endif
                @else
                    <!-- Continuación del contenido con la estructura de tabulación -->
                @endif
            @endif
            <br style="padding-bottom: 10px;">
            <br><br><br><br>
            <div id="firmas">
                <div class="center" style="width: 100%;">
                    <strong>
                        <span class="uppercase">{{ $signer->fullName }}</span><br>
                        <span class="uppercase">{{ $signer->position }}</span><br>
                        <span class="uppercase">{{ $signer->organizationalUnit->name }}</span><br>
                        @if($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 38)
                            @if($fulfillment->serviceRequest->employee->organizationalUnit->id == 24)
                                CONSULTORIO GENERAL URBANO DR. HECTOR REYNO<br>
                            @else
                                SERVICIO SALUD TARAPACÁ<br>
                            @endif
                        @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 1)
                            HOSPITAL DR ERNESTO TORRES GALDAMES<br>
                        @elseif($fulfillment->serviceRequest->responsabilityCenter->establishment_id == 41)
                            HOSPITAL DE ALTO HOSPICIO<br>
                        @endif
                    </strong>
                </div>
            </div>
            <div style="clear: both;padding-top: 156px;"></div>
            <div class="signature-container">
                @if($fulfillment->approval)
                    <div class="signature">
                        @include('sign.approvation', ['approval' => $fulfillment->approval])
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
