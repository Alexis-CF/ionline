<?php setlocale(LC_ALL, 'es'); ?>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Formulario de Requerimientos: Abastecimiento</title>
  <meta name="description" content="">
  <meta name="author" content="Servicio de Salud Iquique">
  <style media="screen">
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 0.75rem;
    }

    .content {
      margin: 0 auto;
      /*border: 1px solid #F2F2F2;*/
      width: 724px;
      /*height: 1100px;*/
    }

    .monospace {
      font-family: "Lucida Console", Monaco, monospace;
    }

    .pie_pagina {
      margin: 0 auto;
      /*border: 1px solid #F2F2F2;*/
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
      margin-top: 80px;
    }

    #firmas>div {
      display: inline-block;
    }

    .li_letras {
      list-style-type: lower-alpha;
    }

    table {
      border: 1px solid grey;
      border-collapse: collapse;
      padding: 0 4px 0 4px;
      width: 100%;
    }

    th,
    td {
      border: 1px solid grey;
      border-collapse: collapse;
      padding: 0 4px 0 4px;
    }

    .column {
      float: left;
      width: 50%;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    @media all {
      .page-break {
        display: none;
      }
    }

    @media print {
      .page-break {
        display: block;
        page-break-before: always;
      }
    }
  </style>
</head>

<body>
  <div class="content">

    <div class="content">
      <img style="padding-bottom: 4px;" src="images/logo_pluma.jpg" width="120" alt="Logo Servicio de Salud"><br>


      <!-- <div class="siete" style="padding-top: 3px;">

        SUBDIRECCIÓN DE GESTIÓN Y DESARROLLO DE LAS PERSONAS
      </div>
      <div class="seis" style="padding-top: 4px;">
        N.I.PHUQHAÑA.
      </div> -->


      <div class="right" style="float: right; width: 340px;">
        <div class="left" style="padding-bottom: 6px;">
          <strong>NÚMERO DE FORMULARIO DE REQUERIMIENTO: {{ number_format($requestForm->id,0,",",".") }}</strong>
        </div>
        <div class="left" style="padding-bottom: 2px;">
          <strong>Iquique, {{ $requestForm->eventSignatureDate('supply_event', 'approved') }}</strong>
        </div>
      </div>


      <div style="clear: both; padding-bottom: 35px">&nbsp;</div>

      <table class="siete">
          <tr>
              <th align="left" style="width: 50%">Gasto Estimado</th>
              <td colspan="2">${{ number_format($requestForm->estimated_expense,0,",",".") }}</td>
          </tr>
          <tr>
              <th align="left">Solicitante</th>
              <td colspan="2">{{ $requestForm->creator ? $requestForm->creator->FullName : 'Usuario eliminado' }}</td>
          </tr>
          <tr>
              <th align="left">Nombre Administrador de Contrato</th>
              <td colspan="2">{{ $requestForm->contractManager ? $requestForm->contractManager->FullName : 'Usuario eliminado' }}</td>
          </tr>
          <tr>
              <th align="left">Teléfono del Administrador de Contrato</th>
              <td colspan="2">
                {{ $requestForm->contractManager ? $requestForm->contractManager->telephones->first()->number : 'Usuario eliminado' }} -
                {{ $requestForm->contractManager ? $requestForm->contractManager->telephones->first()->minsal : 'Usuario eliminado' }}
              </td>
          </tr>
          <tr>
              <th align="left">Correo del Administrador de Contrato</th>
              <td colspan="2">{{ $requestForm->contractManager ? $requestForm->contractManager->email : 'Usuario eliminado' }}</td>
          </tr>
          <tr>
              <th align="left">Departamento y/o Unidad</th>
              <td colspan="2">{{ $requestForm->contractManager ? $requestForm->contractManager->organizationalUnit->name : 'Usuario eliminado' }}</td>
          </tr>
          <tr>
              <th align="left">Subdirección</th>
              <td colspan="2">
                @if($requestForm->contractManager->organizationalUnit->father->level == 2)
                    {{ $requestForm->contractManager ? $requestForm->contractManager->organizationalUnit->father->name : 'Usuario eliminado' }}
                @else
                    -
                @endif

              </td>
          </tr>
          <tr>
              <th align="left">Programa Asociado</th>
              <td colspan="2">{{ $requestForm->program }}</td>
          </tr>
          <tr>
              <th align="left" rowspan="4">Mecanismo de adquisición</th>
              <td>Menores a 3 UTM</td>
              <td align="center">@if($requestForm->getPurchaseMechanism() == 'MENORES A 3 UTM')
                      X
                  @endif
              </td>
          </tr>
          <tr>
              <td>Convenio Marco</td>
              <td align="center">@if($requestForm->getPurchaseMechanism() == 'CONVENIO MARCO')
                      X
                  @endif
              </td>
          </tr>
          <tr>
              <td>Licitación Pública</td>
              <td align="center">@if($requestForm->getPurchaseMechanism() == 'LICITACIÓN PUBLICA')
                      X
                  @endif
              </td>
          </tr>
          <tr>
              <td>Trato Directo</td>
              <td align="center">@if($requestForm->getPurchaseMechanism() == 'TRATO DIRECTO')
                      X
                  @endif
              </td>
          </tr>
      </table>

      <div style="clear: both; padding-bottom: 20px">&nbsp;</div>

      <table class="siete">
          <thead>
              <tr>
                  <th style="width: 5%">Ítem</th>
                  <th style="width: 40%">Articulo</th>
                  <th style="width: 10%">Cantidad</th>
                  <th style="width: 45%">Especificaciones Técnicas</th>
              </tr>
          </thead>
          <tbody>
              @foreach($requestForm->itemRequestForms as $key => $item)
              <tr>
                  <td>{{ $key+1 }}</td>
                  <td>{{ $item->article }}</td>
                  <td align="right">{{ $item->quantity }}</td>
                  <td>{{ $item->specification }}</td>
              </tr>
              @endforeach
          </tbody>
      </table>

      <div style="clear: both; padding-bottom: 20px">&nbsp;</div>

      <table class="siete">
          <thead>
              <tr>
                  <th colspan="2">AUTORIZACIÓN FORMULARIO DE REQUERIMIENTO JEFATURA DIRECTA</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th align="left" style="width: 50%">Nombre</th>
                  <td>{{ $requestForm->eventSigner('leader_ship_event', 'approved')->signerUser->FullName }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Cargo</th>
                  <td>{{ $requestForm->eventSigner('leader_ship_event', 'approved')->position_signer_user }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Fecha</th>
                  <td>{{ $requestForm->eventSigner('leader_ship_event', 'approved')->signature_date->format('d-m-Y H:i') }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Firma</th>
                  <td></td>
              </tr>
          </tbody>
      </table>

      <div style="clear: both; padding-bottom: 20px">&nbsp;</div>

      <table class="siete">
          <thead>
              <tr>
                  <th colspan="2">CERTIFICADO DE REFRENDACIÓN PRESUPUESTARIA</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th align="left" style="width: 50%">PROGRAMA ASOCIADO</th>
                  <td>{{ $requestForm->program }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Folio Requerimiento - SIGFE</th>
                  <td>{{ $requestForm->sigfe }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Folio Compromiso SIGFE </th>
                  <td></td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Ítem Presupuestario</th>
                  <td>
                      @foreach($requestForm->itemRequestForms as $item)
                          {{ $item->budgetItem->code }}
                      @endforeach
                  </td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Monto $</th>
                  <td>${{ number_format($requestForm->estimated_expense,0,",",".") }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Fecha</th>
                  <td>{{ $requestForm->eventSigner('pre_finance_event', 'approved')->signature_date->format('d-m-Y H:i') }}</td>
              </tr>
          </tbody>
      </table>

      <div style="clear: both; padding-bottom: 20px">&nbsp;</div>

      <table class="siete">
          <thead>
              <tr>
                  <th colspan="2">AUTORIZACIÓN FINANCIERA</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th align="left" style="width: 50%">Nombre</th>
                  <td>{{ $requestForm->eventSigner('finance_event', 'approved')->signerUser->FullName }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Cargo</th>
                  <td>{{ $requestForm->eventSigner('finance_event', 'approved')->position_signer_user }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Fecha</th>
                  <td>{{ $requestForm->eventSigner('finance_event', 'approved')->signature_date->format('d-m-Y H:i') }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Firma</th>
                  <td></td>
              </tr>
          </tbody>
      </table>

      <div style="clear: both; padding-bottom: 20px">&nbsp;</div>

      <table class="siete">
          <thead>
              <tr>
                  <th colspan="2">AUTORIZACIÓN ASIGNACIÓN DEL FORMULARIO DE REQUERIMIENTO </th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <th align="left" style="width: 50%">Nombre</th>
                  <td>{{ $requestForm->eventSigner('supply_event', 'approved')->signerUser->FullName }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Cargo</th>
                  <td>{{ $requestForm->eventSigner('supply_event', 'approved')->position_signer_user }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Fecha</th>
                  <td>{{ $requestForm->eventSigner('supply_event', 'approved')->signature_date->format('d-m-Y H:i') }}</td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">Firma</th>
                  <td></td>
              </tr>
              <tr>
                  <th align="left" style="width: 50%">COMPRADOR ASIGNADO: </th>
                  <td>{{ $requestForm->supervisor->FullName }}</td>
              </tr>
          </tbody>
      </table>

    </div>
</body>

</html>
