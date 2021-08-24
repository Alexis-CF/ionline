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
          @if(!$request_to_sign->Where('id', $request->id)->isEmpty())
          @foreach($request_to_sign->Where('id', $request->id) as $modal_request)
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
                              {{ $modal_request->organizationalUnit->name }}
                          </td>
                      </tr>
                      <tr>
                          <th class="table-active">Solicita autorizar el llamado a presentar antecedentes al cargo de</th>
                          <td colspan="2">
                              {{ $modal_request->name }}
                          </td>
                      </tr>
                      <tr>
                          <th class="table-active">En el grado</th>
                          <td colspan="2">{{ $modal_request->degree }}</td>
                      </tr>
                      <tr>
                          <th class="table-active">Calidad Jurídica</th>
                          <td colspan="2">{{ $modal_request->LegalQualityValue }}</td>
                      </tr>
                      <tr>
                          <th class="table-active">La Persona cumplirá labores en Jornada</th>
                          <td style="width: 33%">{{ $modal_request->WorkDayValue }}</td>
                          <td style="width: 33%">{{ $modal_request->other_work_day }}</td>
                      </tr>
                      <tr>
                          <th class="table-active">Justificación o fundamento de la Contratación</th>
                          <td style="width: 33%">{{ $modal_request->FundamentValue }}</td>
                          <td style="width: 33%">De funcionario: {{ $modal_request->name_to_replace }}</td>
                      </tr>
                      <tr>
                          <th class="table-active">Otros (especifique)</th>
                          <td colspan="2">{{ $modal_request->other_fundament }}</td>
                      </tr>
                      <tr>
                          <td colspan="3">El documento debe contener las firmas y timbres de las personas que dan autorización para que la Unidad Selección inicie el proceso de Llamado de presentación de antecedentes.</td>
                      </tr>
                      <tr>
                          @foreach($modal_request->RequestSign as $sign)
                            <td class="table-active text-center">
                                {{ $sign->organizationalUnit->name }}<br>
                            </td>
                          @endforeach
                      </tr>
                      <tr>
                          @foreach($modal_request->RequestSign as $requestSign)
                            <td align="center">
                                @if($requestSign->request_status == 'pending' && $requestSign->organizational_unit_id == Auth::user()->organizationalUnit->id)
                                    Estado: {{ $requestSign->StatusValue }} <br><br>
                                    <div class="row">
                                        <div class="col-sm">
                                            <form method="POST" class="form-horizontal" action="{{ route('replacement_staff.request.sign.update', [$requestSign, 'status' => 'accepted']) }}">
                                                  @csrf
                                                  @method('PUT')
                                                  <button type="submit" class="btn btn-success btn-sm"
                                                      onclick="return confirm('¿Está seguro que desea Aceptar la solicitud?')"
                                                      title="Aceptar">
                                                      <i class="fas fa-check-circle"></i></a>
                                                  </button>
                                            </form>
                                        </div>
                                        <div class="col-sm">
                                          <p>
                                            <a class="btn btn-danger btn-sm" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                          </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body">
                                                  <form method="POST" class="form-horizontal" action="{{ route('replacement_staff.request.sign.update', [$requestSign, 'status' => 'rejected']) }}">
                                                      @csrf
                                                      @method('PUT')
                                                      <div class="form-group">
                                                          <label class="float-left" for="for_observation">Motivo Rechazo</label>
                                                          <textarea class="form-control" id="for_observation" name="observation" rows="2"></textarea>
                                                      </div>
                                                      <button type="submit" class="btn btn-danger btn-sm float-right"
                                                          onclick="return confirm('¿Está seguro que desea Rechazar la solicitud?')"
                                                          title="Rechazar">
                                                          <i class="fas fa-times-circle"></i> Rechazar</a>
                                                      </button>
                                                  </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($requestSign->request_status == 'accepted')
                                    <span style="color: green;">
                                      <i class="fas fa-check-circle"></i> {{ $requestSign->StatusValue }}
                                    </span> <br>
                                    <i class="fas fa-user"></i> {{ $requestSign->user->FullName }}<br>
                                    <i class="fas fa-calendar-alt"></i> {{ $requestSign->date_sign->format('d-m-Y H:i:s') }}<br>
                                @else
                                    @if($requestSign->request_status == NULL)
                                        <i class="fas fa-ban"></i> No disponible para Aprobación.<br>
                                    @else
                                        <i class="fas fa-clock"></i> {{ $requestSign->StatusValue }}<br>
                                    @endif
                                @endif
                            </td>
                          @endforeach
                      </tr>
                  </tbody>
              </table>
          @endforeach
          @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
