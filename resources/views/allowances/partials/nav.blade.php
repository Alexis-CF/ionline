<ul class="nav nav-tabs mb-3 d-print-none">

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('allowances.index*') || request()->routeIs('allowances.create*') || request()->routeIs('allowances.all_index*') || request()->routeIs('allowances.sign_index*')  ? 'active' : '' }}" href="#" id="navbardrop" data-toggle="dropdown">
                <i class="fas fa-wallet"></i> Viáticos
            </a>

            <div class="dropdown-menu">
                <a class="dropdown-item {{ active(['allowances.index']) }}" href="{{ route('allowances.index') }}"><i class="fas fa-wallet"></i> Mis víaticos</a>
                <a class="dropdown-item {{ active(['allowances.create']) }}" href="{{ route('allowances.create') }}"><i class="fas fa-plus"></i> Nueva Solicitud</a>
                @can('Allowances: all')
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item {{ active(['allowances.all_index']) }}" href="{{ route('allowances.all_index') }}"><i class="fas fa-wallet"></i> Todos los víaticos</a>
                @endcan
                
                @if(Auth::user()->can('Allowances: sirh'))
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item {{ active(['allowances.sign_index']) }}" href="{{ route('allowances.sign_index') }}"><i class="fas fa-keyboard"></i> Revisión SIRH</a>
                @endif
            </div>
        </li>

        @if(Auth::user()->hasPermissionTo('Allowances: director') || count(App\Rrhh\Authority::getAmIAuthorityFromOu(now(),['manager'],Auth::user()->id)) > 0)
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('allowances.director_index*')  ? 'active' : '' }}" href="#" id="navbardrop" data-toggle="dropdown">
                <i class="fas fa-wallet"></i> Mis Aprobaciones
            </a>

            @can('Allowances: director')
            <div class="dropdown-menu">
                <a class="dropdown-item {{ active(['allowances.director_index']) }}" href="{{ route('allowances.director_index') }}"><i class="fas fa-wallet"></i> Dirección</a>
            </div>
            @endcan
            
        </li>
        @endif

        @can('Allowances: reports')
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ active(['allowances.reports.*']) }}" href="#" id="navbardrop" data-toggle="dropdown">
                <i class="fas fa-file-export"></i> Reportes
            </a>

            <div class="dropdown-menu">
                <a class="dropdown-item {{ active(['allowances.reports.create_by_dates']) }}" href="{{ route('allowances.reports.create_by_dates') }}">
                    <i class="fas fa-calendar"></i> Viáticos por fecha
                </a>

                {{-- @can('Allowances: reports') --}}
                <a class="dropdown-item {{ active(['allowances.import']) }}" href="{{ route('allowances.import') }}">
                    <i class="fas fa-calendar"></i> Importar
                </a>
                {{-- @endcan --}}
            </div>
        </li>
        @endcan
</ul>
