<!-- Modal -->
<div class="modal fade" id="exampleModalCenter-req-{{ $request->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Gestión de Solicitudes para aprobación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class="table table-sm table-bordered">
              <thead>
                  <tr class="table-active">
                    <th colspan="3">Formulario Solicitud Contratación de Personal</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <th class="table-active">Por medio del presente, la</th>
                      <td colspan="2">
                          {{ $request->organizationalUnit->name }}
                      </td>
                  </tr>
                  <tr>
                      <th class="table-active">Solicita autorizar el llamado a presentar antecedentes al cargo de</th>
                      <td colspan="2">
                          {{ $request->name }}
                      </td>
                  </tr>
                  <tr>
                      <th class="table-active">En el grado</th>
                      <td colspan="2">{{ $request->degree }}</td>
                  </tr>
                  <tr>
                      <th class="table-active">Calidad Jurídica</th>
                      <td colspan="2">{{ $request->LegalQualityValue }}</td>
                  </tr>
                  <tr>
                      <th class="table-active">La Persona cumplirá labores en Jornada</th>
                      <td style="width: 33%">{{ $request->WorkDayValue }}</td>
                      <td style="width: 33%">{{ $request->other_work_day }}</td>
                  </tr>
                  <tr>
                      <th class="table-active">Justificación o fundamento de la Contratación</th>
                      <td style="width: 33%">{{ $request->FundamentValue }}</td>
                      <td style="width: 33%">De funcionario: {{ $request->name_to_replace }}</td>
                  </tr>
                  <tr>
                      <th class="table-active">Otros (especifique)</th>
                      <td colspan="2">{{ $request->other_fundament }}</td>
                  </tr>
                  <tr>
                      <td colspan="3">El documento debe contener las firmas y timbres de las personas que dan autorización para que la Unidad Selección inicie el proceso de Llamado de presentación de antecedentes.</td>
                  </tr>
                  <tr>
                      @foreach($request->RequestSign as $sign)
                        <td class="table-active text-center">
                            {{ $sign->organizationalUnit->name }}<br>
                        </td>
                      @endforeach
                  </tr>
                  <tr>
                      @foreach($request->RequestSign as $requestSign)
                        <td align="center">
                            @if($requestSign->request_status == 'accepted')
                                <span style="color: green;">
                                  <i class="fas fa-check-circle"></i> {{ $requestSign->StatusValue }} </span><br>
                                <i class="fas fa-user"></i> {{ $requestSign->user->FullName }}<br>
                                <i class="fas fa-calendar-alt"></i> {{ $requestSign->date_sign->format('d-m-Y H:i:s') }}<br>
                            @endif
                            @if($requestSign->request_status == 'rejected')
                                <span style="color: Tomato;">
                                  <i class="fas fa-times-circle"></i> {{ $requestSign->StatusValue }} </span><br>
                                <i class="fas fa-user"></i> {{ $requestSign->user->FullName }}<br>
                                <i class="fas fa-calendar-alt"></i> {{ $requestSign->date_sign->format('d-m-Y H:i:s') }}<br>
                                <hr>
                                {{ $requestSign->observation }}<br>
                            @endif
                            @if($requestSign->request_status == 'pending' || $requestSign->request_status == NULL)
                                <i class="fas fa-clock"></i> Pendiente.<br>
                            @endif
                        </td>
                      @endforeach
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
