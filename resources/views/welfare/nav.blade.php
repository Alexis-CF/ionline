<ul class="nav nav-tabs mb-3 d-print-none">

    @canany(['amiPASS', 'Rrhh: welfare'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.index') }}">
                <i class="fas fa-home"></i> home
            </a>
        </li>
    @endcanany


    @canany(['Rrhh: welfare'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.balances') }}">
                <i class="fas fa-balance-scale"></i> Balance
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.dosfile.index') }}">
                <i class="fas fa-file-upload"></i> Carga archivo DOS
            </a>
        </li>

        <!--li class="nav-item">
                <a class="nav-link" href="{{ route('welfare.loans.index') }}">
                    <i class="fas fa-file-excel"></i> Carga Excel de préstamos
                </a>
            </li-->

        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.report') }}">
                <i class="fas fa-chart-bar"></i> Gráficos
            </a>
        </li>

        @if(auth()->user()->welfare == 1)
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-hands-helping"></i> Beneficios </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        
                        <a class="dropdown-item" href="{{ route('welfare.benefits.benefits') }}">
                            <i class="fas fa-plus-square"></i> Mantenedor de beneficios
                        </a>

                        <a class="dropdown-item" href="{{ route('welfare.benefits.subsidies') }}">
                            <i class="fas fa-plus-square"></i> Mantenedor de subsidios
                        </a>

                        <a class="dropdown-item" href="{{ route('welfare.benefits.requests') }}">
                            <i class="fa fa-user-plus" aria-hidden="true"></i> Mis solicitudes
                        </a>

                        <a class="dropdown-item" href="{{ route('welfare.benefits.requests-admin') }}">
                            <i class="fa fa-users" aria-hidden="true"></i> Administrador de solicitudes
                        </a>

                    </div>
            </li>
        @endif
        
    @endcanany

    @if(auth()->user()->welfare == 1)
        @canany(['HotelBooking: Administrador', 'HotelBooking: User'])
            <li class="nav-item">
                <a class="nav-link" href="{{route('hotel_booking.index') }}">
                    <i class="fa fa-bed"></i> Reserva Cabañas
                </a>
            </li>
        @endcanany
    @endif

    @canany(['amiPASS'])
        <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-file-invoice"></i> amiPASS</a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('welfare.amipass.upload') }}">
                    <i class="fas fa-file-upload"></i> Carga Archivos
                </a>

                <a class="dropdown-item" href="{{ route('welfare.amipass.shifts-index') }}">
                    <i class="fas fa-list"></i> Carga Turnos
                </a>

                <a class="dropdown-item" href="{{ route('welfare.amipass.dashboard') }}">
                    <i class="fas fa-file-upload"></i> Dashboard
                </a>

                <a class="dropdown-item" href="{{ route('welfare.amipass.value.indexValue') }}">
                    <i class="fas fa-calendar-alt"></i> Valor de Carga Amipass Anual
                </a>
                
                <a class="dropdown-item" href="{{ route('welfare.amipass.report-by-dates') }}">
                    <i class="fas fa-calendar-alt"></i> Reporte por fechas
                </a>

                <a class="dropdown-item" href="{{ route('welfare.amipass.report-by-employee') }}">
                    <i class="fas fa-calendar-alt"></i> Reporte por funcionario
                </a>
            </div>
        </li>
    @endcanany

    @canany(['amiPASS', 'Rrhh: welfare'])
    
        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.amipass.requests-manager') }}">
                <i class="fas fa-utensils"></i> Solicitudes Amipass
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ route('welfare.amipass.question-all-index') }}">
                <i class="fas fa-question-circle"></i> Consultas/Sugerencia Amipass
            </a>
        </li>

    @endcanany

    @canany(['Welfare: welfare users import'])
    <li class="nav-item">
        <a class="nav-link" href="{{ route('welfare.welfare-users-import') }}">
            <i class="fas fa-file-upload"></i> Importar usuarios bienestar
        </a>
    </li>
    @endcanany

    </ul>
