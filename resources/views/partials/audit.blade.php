<h6 class="mt-3 mt-4">Historial de cambios</h6>
<div class="table-responsive-md">
    <table class="table table-sm small text-muted mt-3">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Modificaciones</th>
            </tr>
        </thead>
        <tbody>
            @if($audits && $audits->count() > 0)
                @foreach($audits->sortByDesc('updated_at') as $audit)
                <tr>
                    <td nowrap>{{ $audit->created_at }}</td>
                    <td nowrap>{{ optional($audit->user)->tinnyName }}</td>
                    <td>
                        @foreach($audit->old_values as $attribute => $value )
                        <strong>{{ $attribute }}</strong> : {{ $value }} => {{ $audit->new_values[$attribute] }} <br>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
