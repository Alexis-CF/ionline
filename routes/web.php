<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false, 'logout' => false]);

Route::get('logout','Auth\LoginController@logout')->name('logout');

Route::get('/claveunica','ClaveUnicaController@autenticar')->name('claveunica.autenticar');
Route::get('/claveunica/callback','ClaveUnicaController@callback')->name('claveunica.callback');
Route::get('/claveunica/login/{access_token}','ClaveUnicaController@login')->name('claveunica.login');

Route::get('/home', 'HomeController@index')->name('home');


//Route::get('foo/oscar', function () {return 'Hello World';})->name('lanterna');
Route::prefix('resources')->name('resources.')->namespace('Resources')->middleware('auth')->group(function(){
    //Route::get('report','ComputerController@report')->name('report');
    Route::prefix('telephones')->name('telephone.')->group(function(){
        Route::get('/', 'TelephoneController@index')->name('index');
        Route::get('create', 'TelephoneController@create')->name('create');
        //Route::get('telephones','TelephoneController@index')->name('telephone.index');
        //Route::resource('computers','ComputerController');
        //Route::resource('printers','PrinterController');
        //Route::resource('wingles','WingleController');
        //Route::resource('mobiles','MobileController');
    });
});

Route::prefix('rrhh')->as('rrhh.')->group(function () {
    Route::get('{user}/roles', 'Rrhh\RoleController@index')->name('roles.index')->middleware('auth');
    Route::post('{user}/roles','Rrhh\RoleController@attach')->name('roles.attach')->middleware('auth');

    Route::resource('authorities','Rrhh\AuthorityController')->middleware(['auth']);;

    Route::prefix('organizational-units')->name('organizational-units.')->group(function () {
        Route::get('/', 'Rrhh\OrganizationalUnitController@index')->name('index');
        Route::get('/create', 'Rrhh\OrganizationalUnitController@create')->name('create');
        Route::post('/store', 'Rrhh\OrganizationalUnitController@store')->name('store');
        Route::get('{organizationalUnit}/edit', 'Rrhh\OrganizationalUnitController@edit')->name('edit');
        Route::put('{organizationalUnit}', 'Rrhh\OrganizationalUnitController@update')->name('update');
        Route::get('{organizationalUnit}/destroy', 'Rrhh\OrganizationalUnitController@destroy')->name('destroy');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('ou/{ou_id?}','Rrhh\UserController@getFromOu')->name('get.from.ou')->middleware('auth');
        Route::get('autority/{ou_id?}','Rrhh\UserController@getAutorityFromOu')->name('get.autority.from.ou')->middleware('auth');
        Route::put('{user}/password', 'Rrhh\UserController@resetPassword')->name('password.reset')->middleware('auth');
        Route::get('{user}/switch','Rrhh\UserController@switch')->name('switch')->middleware('auth');
        Route::get('directory', 'Rrhh\UserController@directory')->name('directory');
        Route::get('/', 'Rrhh\UserController@index')->name('index')->middleware('auth');
        Route::get('/create', 'Rrhh\UserController@create')->name('create')->middleware('auth');
        Route::post('/', 'Rrhh\UserController@store')->name('store')->middleware('auth');
        Route::get('/{user}/edit', 'Rrhh\UserController@edit')->name('edit')->middleware('auth');
        Route::put('/{user}', 'Rrhh\UserController@update')->name('update')->middleware('auth');
        Route::delete('/{user}', 'Rrhh\UserController@destroy')->name('destroy')->middleware('auth');
    });
});

Route::prefix('parameters')->as('parameters.')->middleware('auth')->group(function(){
    Route::get('/', 'Parameters\ParameterController@index')->name('index');
    Route::put('/{parameter}', 'Parameters\ParameterController@update')->name('update');
    Route::get('drugs', 'Parameters\ParameterController@indexDrugs')->name('drugs')->middleware(['role:Drugs: admin']);
    Route::resource('permissions','Parameters\PermissionController');
    Route::resource('roles','Parameters\RoleController');

    Route::prefix('communes')->as('communes.')->group(function (){
        Route::get('/', 'Parameters\CommuneController@index')->name('index');
        Route::put('/{commune}', 'Parameters\CommuneController@update')->name('update');
    });

    Route::prefix('establishments')->as('establishments.')->group(function (){
        Route::get('/', 'Parameters\EstablishmentController@index')->name('index');
        Route::put('/{establishment}', 'Parameters\EstablishmentController@update')->name('update');
    });

    Route::prefix('holidays')->as('holidays.')->group(function (){
        Route::get('/', 'Parameters\HolidayController@index')->name('index');
        Route::put('/{holiday}', 'Parameters\HolidayController@update')->name('update');

    });

    Route::resource('locations','Parameters\LocationController');
    Route::resource('places','Parameters\PlaceController');
    Route::resource('phrases','Parameters\PhraseOfTheDayController');



});

Route::prefix('indicators')->as('indicators.')->group(function(){
    Route::get('/', function () { return view('indicators.index'); })->name('index');
    Route::get('single_parameter/comgescreate2020/{id}/{indicador}/{mes}/{nd}', 'Indicators\SingleParameterController@comges')->name('comgescreate2020')->middleware('auth');

    Route::resource('single_parameter', 'Indicators\SingleParameterController')->middleware('auth');
    Route::prefix('comges')->as('comges.')->group(function(){
        Route::get('/', function () { return view('indicators.comges.index'); })->name('index');

        Route::prefix('2020')->as('2020.')->group(function(){
            Route::get('/', 'Indicators\_2020\ComgesController@index')->name('index');

            //COMGES 1
            Route::get('/comges1', 'Indicators\_2020\ComgesController@comges1')->name('comges1');
            Route::get('/comges1corte1', 'Indicators\_2020\ComgesController@comges1corte1')->name('comges1corte1');

            //COMGES 2
            Route::get('/comges2', 'Indicators\_2020\ComgesController@comges2')->name('comges2');
            Route::get('/comges2corte1', 'Indicators\_2020\ComgesController@comges2corte1')->name('comges2corte1');

            //COMGES 3
            Route::get('/comges3', 'Indicators\_2020\ComgesController@comges3')->name('comges3');
            Route::get('/comges3corte1', 'Indicators\_2020\ComgesController@comges3corte1')->name('comges3corte1');

            //COMGES 4
            Route::get('/comges4', 'Indicators\_2020\ComgesController@comges4')->name('comges4');
            Route::get('/comges4corte1', 'Indicators\_2020\ComgesController@comges4corte1')->name('comges4corte1');

            //COMGES 5
            Route::get('/comges5', 'Indicators\_2020\ComgesController@comges5')->name('comges5');
            Route::get('/comges5corte1', 'Indicators\_2020\ComgesController@comges5corte1')->name('comges5corte1');

            //COMGES 6
            Route::get('/comges6', 'Indicators\_2020\ComgesController@comges6')->name('comges6');
            Route::get('/comges6corte1', 'Indicators\_2020\ComgesController@comges6corte1')->name('comges6corte1');

            //COMGES 7
            Route::get('/comges7', 'Indicators\_2020\ComgesController@comges7')->name('comges7');
            Route::get('/comges7corte1', 'Indicators\_2020\ComgesController@comges7corte1')->name('comges7corte1');

            //COMGES 8
            Route::get('/comges8', 'Indicators\_2020\ComgesController@comges8')->name('comges8');
            Route::get('/comges8corte1', 'Indicators\_2020\ComgesController@comges8corte1')->name('comges8corte1');

            //COMGES 9
            Route::get('/comges9', 'Indicators\_2020\ComgesController@comges9')->name('comges9');
            Route::get('/comges9corte1', 'Indicators\_2020\ComgesController@comges9corte1')->name('comges9corte1');

            //COMGES 10
            Route::get('/comges10', 'Indicators\_2020\ComgesController@comges10')->name('comges10');
            Route::get('/comges10corte1', 'Indicators\_2020\ComgesController@comges10corte1')->name('comges10corte1');

            //COMGES 11
            Route::get('/comges11', 'Indicators\_2020\ComgesController@comges11')->name('comges11');
            Route::get('/comges11corte1', 'Indicators\_2020\ComgesController@comges11corte1')->name('comges11corte1');

            //COMGES 12
            Route::get('/comges12', 'Indicators\_2020\ComgesController@comges12')->name('comges12');
            Route::get('/comges12corte1', 'Indicators\_2020\ComgesController@comges12corte1')->name('comges12corte1');

            //COMGES 13
            Route::get('/comges13', 'Indicators\_2020\ComgesController@comges13')->name('comges13');
            Route::get('/comges13corte1', 'Indicators\_2020\ComgesController@comges13corte1')->name('comges13corte1');

            //COMGES 14
            Route::get('/comges14', 'Indicators\_2020\ComgesController@comges14')->name('comges14');
            Route::get('/comges14corte1', 'Indicators\_2020\ComgesController@comges14corte1')->name('comges14corte1');

            //COMGES 15
            Route::get('/comges15', 'Indicators\_2020\ComgesController@comges15')->name('comges15');
            Route::get('/comges15corte1', 'Indicators\_2020\ComgesController@comges15corte1')->name('comges15corte1');

            //COMGES 16
            Route::get('/comges16', 'Indicators\_2020\ComgesController@comges16')->name('comges16');
            Route::get('/comges16corte1', 'Indicators\_2020\ComgesController@comges16corte1')->name('comges16corte1');

            //COMGES 17
            Route::get('/comges17', 'Indicators\_2020\ComgesController@comges17')->name('comges17');
            Route::get('/comges17corte1', 'Indicators\_2020\ComgesController@comges17corte1')->name('comges17corte1');

            //COMGES 18
            Route::get('/comges18', 'Indicators\_2020\ComgesController@comges18')->name('comges18');
            Route::get('/comges18corte1', 'Indicators\_2020\ComgesController@comges18corte1')->name('comges18corte1');

            //COMGES 19
            Route::get('/comges19', 'Indicators\_2020\ComgesController@comges19')->name('comges19');
            Route::get('/comges19corte1', 'Indicators\_2020\ComgesController@comges19corte1')->name('comges19corte1');

            //COMGES 21
            Route::get('/comges21', 'Indicators\_2020\ComgesController@comges21')->name('comges21');
            Route::get('/comges21corte1', 'Indicators\_2020\ComgesController@comges21corte1')->name('comges21corte1');

            //COMGES 22
            Route::get('/comges22', 'Indicators\_2020\ComgesController@comges22')->name('comges22');
            Route::get('/comges22corte1', 'Indicators\_2020\ComgesController@comges22corte1')->name('comges22corte1');

            //COMGES 24
            Route::get('/comges24', 'Indicators\_2020\ComgesController@comges24')->name('comges24');
            Route::get('/comges24corte1', 'Indicators\_2020\ComgesController@comges24corte1')->name('comges24corte1');

            //COMGES 25
            Route::get('/comges25', 'Indicators\_2020\ComgesController@comges25')->name('comges25');
            Route::get('/comges25corte1', 'Indicators\_2020\ComgesController@comges25corte1')->name('comges25corte1');

            Route::get('/servicio', 'Indicators\_2018\Indicator19664Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2018\Indicator19664Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2018\Indicator19664Controller@reyno')->name('reyno');
        });

    });

    Route::prefix('19813')->as('19813.')->group(function(){
        Route::get('/', function () { return view('indicators.19813.index'); })->name('index');

        Route::prefix('2018')->as('2018.')->group(function(){
            //Route::get('', 'Indicators\IndicatorController@index_19813')->name('index');
            Route::get('/', 'Indicators\_2018\Indicator19813Controller@index')->name('index');

            Route::get('/indicador1', 'Indicators\_2018\Indicator19813Controller@indicador1')->name('indicador1');
            Route::get('/indicador2', 'Indicators\_2018\Indicator19813Controller@indicador2')->name('indicador2');
            Route::get('/indicador3a', 'Indicators\_2018\Indicator19813Controller@indicador3a')->name('indicador3a');
            Route::get('/indicador3b', 'Indicators\_2018\Indicator19813Controller@indicador3b')->name('indicador3b');
            Route::get('/indicador3c', 'Indicators\_2018\Indicator19813Controller@indicador3c')->name('indicador3c');
            Route::get('/indicador4a', 'Indicators\_2018\Indicator19813Controller@indicador4a')->name('indicador4a');
            Route::get('/indicador4b', 'Indicators\_2018\Indicator19813Controller@indicador4b')->name('indicador4b');
            Route::get('/indicador5', 'Indicators\_2018\Indicator19813Controller@indicador5')->name('indicador5');
            Route::get('/indicador6', 'Indicators\_2018\Indicator19813Controller@indicador6')->name('indicador6');
        });

        Route::prefix('2019')->as('2019.')->group(function(){
            Route::get('/',           'Indicators\_2019\Indicator19813Controller@index')->name('index');
            Route::get('/indicador1', 'Indicators\_2019\Indicator19813Controller@indicador1')->name('indicador1');
            Route::get('/indicador2', 'Indicators\_2019\Indicator19813Controller@indicador2')->name('indicador2');
            Route::get('/indicador3a','Indicators\_2019\Indicator19813Controller@indicador3a')->name('indicador3a');
            Route::get('/indicador3b','Indicators\_2019\Indicator19813Controller@indicador3b')->name('indicador3b');
            Route::get('/indicador3c','Indicators\_2019\Indicator19813Controller@indicador3c')->name('indicador3c');
            Route::get('/indicador4a','Indicators\_2019\Indicator19813Controller@indicador4a')->name('indicador4a');
            Route::get('/indicador4b','Indicators\_2019\Indicator19813Controller@indicador4b')->name('indicador4b');
            Route::get('/indicador5', 'Indicators\_2019\Indicator19813Controller@indicador5')->name('indicador5');
            Route::get('/indicador6', 'Indicators\_2019\Indicator19813Controller@indicador6')->name('indicador6');
        });

        Route::prefix('2020')->as('2020.')->group(function(){
            Route::get('/',           'Indicators\_2020\Indicator19813Controller@index')->name('index');
            Route::get('/indicador1', 'Indicators\_2020\Indicator19813Controller@indicador1')->name('indicador1');
            Route::get('/indicador2', 'Indicators\_2020\Indicator19813Controller@indicador2')->name('indicador2');
            Route::get('/indicador3a','Indicators\_2020\Indicator19813Controller@indicador3a')->name('indicador3a');
            Route::get('/indicador3b','Indicators\_2020\Indicator19813Controller@indicador3b')->name('indicador3b');
            Route::get('/indicador3c','Indicators\_2020\Indicator19813Controller@indicador3c')->name('indicador3c');
            Route::get('/indicador4a','Indicators\_2020\Indicator19813Controller@indicador4a')->name('indicador4a');
            Route::get('/indicador4b','Indicators\_2020\Indicator19813Controller@indicador4b')->name('indicador4b');
            Route::get('/indicador5', 'Indicators\_2020\Indicator19813Controller@indicador5')->name('indicador5');
            Route::get('/indicador6', 'Indicators\_2020\Indicator19813Controller@indicador6')->name('indicador6');
        });
    });

    Route::prefix('19664')->as('19664.')->group(function(){
        Route::get('/', function () { return view('indicators.19664.index'); })->name('index');

        Route::prefix('2018')->as('2018.')->group(function(){
            Route::get('/', 'Indicators\_2018\Indicator19664Controller@index')->name('index');
            Route::get('/servicio', 'Indicators\_2018\Indicator19664Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2018\Indicator19664Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2018\Indicator19664Controller@reyno')->name('reyno');
        });

        Route::prefix('2019')->as('2019.')->group(function(){
            Route::get('/', 'Indicators\_2019\Indicator19664Controller@index')->name('index');
            Route::get('/servicio', 'Indicators\_2019\Indicator19664Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2019\Indicator19664Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2019\Indicator19664Controller@reyno')->name('reyno');
        });

        Route::prefix('2020')->as('2020.')->group(function(){
            Route::get('/', 'Indicators\_2020\Indicator19664Controller@index')->name('index');
            Route::get('/servicio', 'Indicators\_2020\Indicator19664Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2020\Indicator19664Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2020\Indicator19664Controller@reyno')->name('reyno');
        });
    });

    Route::prefix('18834')->as('18834.')->group(function() {
        Route::get('/', function () { return view('indicators.18834.index'); })->name('index');

        Route::prefix('2018')->as('2018.')->group(function(){
            //Route::get('', 'Indicators\_2018\Indicator18834Controller@index_18834')->name('index');
            Route::get('/', 'Indicators\_2018\Indicator18834Controller@index')->name('index');

            Route::get('/servicio', 'Indicators\_2018\Indicator18834Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2018\Indicator18834Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2018\Indicator18834Controller@reyno')->name('reyno');
        });

        Route::prefix('2019')->as('2019.')->group(function(){
            //Route::get('', 'Indicators\_2018\Indicator18834Controller@index_18834')->name('index');
            Route::get('/', 'Indicators\_2019\Indicator18834Controller@index')->name('index');

            Route::get('/servicio', 'Indicators\_2019\Indicator18834Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2019\Indicator18834Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2019\Indicator18834Controller@reyno')->name('reyno');
        });

        Route::prefix('2020')->as('2020.')->group(function(){
            //Route::get('', 'Indicators\_2018\Indicator18834Controller@index_18834')->name('index');
            Route::get('/', 'Indicators\_2020\Indicator18834Controller@index')->name('index');

            Route::get('/servicio', 'Indicators\_2020\Indicator18834Controller@servicio')->name('servicio');
            Route::get('/hospital', 'Indicators\_2020\Indicator18834Controller@hospital')->name('hospital');
            Route::get('/reyno', 'Indicators\_2020\Indicator18834Controller@reyno')->name('reyno');
        });

    });

    Route::prefix('program_aps')->as('program_aps.')->group(function(){
        Route::get('/', function () { return view('indicators.program_aps.index'); })->name('index');
        Route::prefix('2018')->as('2018.')->group(function(){
            Route::get('/','Indicators\_2018\ProgramApsValueController@index')->name('index');
            Route::get('/create','Indicators\_2018\ProgramApsValueController@create')->name('create');
            Route::post('/','Indicators\_2018\ProgramApsValueController@store')->name('store');
            Route::get('/{glosa}/{commune}/edit','Indicators\_2018\ProgramApsValueController@edit')->name('edit');
            Route::put('/{programApsValue}','Indicators\_2018\ProgramApsValueController@update')->name('update');
        });
        Route::prefix('2019')->as('2019.')->group(function(){
            Route::get('/','Indicators\_2019\ProgramApsValueController@index')->name('index');
            Route::get('/create','Indicators\_2019\ProgramApsValueController@create')->name('create')->middleware('auth');
            Route::post('/','Indicators\_2019\ProgramApsValueController@store')->name('store')->middleware('auth');
            Route::get('/{glosa}/{commune}/edit','Indicators\_2019\ProgramApsValueController@edit')->name('edit')->middleware('auth');
            Route::put('/{programApsValue}','Indicators\_2019\ProgramApsValueController@update')->name('update')->middleware('auth');
        });
        Route::prefix('2020')->as('2020.')->group(function(){
            Route::get('/','Indicators\_2020\ProgramApsValueController@index')->name('index');
            Route::get('/create','Indicators\_2020\ProgramApsValueController@create')->name('create')->middleware('auth');
            Route::post('/','Indicators\_2020\ProgramApsValueController@store')->name('store')->middleware('auth');
            Route::get('/{glosa}/{commune}/edit','Indicators\_2020\ProgramApsValueController@edit')->name('edit')->middleware('auth');
            Route::put('/{programApsValue}','Indicators\_2020\ProgramApsValueController@update')->name('update')->middleware('auth');
        });
    });

    Route::prefix('aps')->as('aps.')->group(function(){
        Route::get('/', function () { return view('indicators.aps.index'); })->name('index');
        Route::prefix('2020')->as('2020.')->group(function() {
            Route::get('/', 'Indicators\_2020\IndicatorAPSController@index')->name('index');
            Route::get('/pmasama', 'Indicators\_2020\IndicatorAPSController@pmasama')->name('pmasama');

            Route::prefix('chcc')->as('chcc.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorChccController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorChccController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorChccController@reyno')->name('reyno');
                Route::get('/hospital', 'Indicators\_2020\IndicatorChccController@hospital')->name('hospital');
            });

            Route::prefix('depsev')->as('depsev.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorDepsevController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorDepsevController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorDepsevController@reyno')->name('reyno');
            });

            Route::prefix('saserep')->as('saserep.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorSaserepController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorSaserepController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorSaserepController@reyno')->name('reyno');
                Route::get('/hospital', 'Indicators\_2020\IndicatorSaserepController@hospital')->name('hospital');
            });

            Route::prefix('ges_odont')->as('ges_odont.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorGesOdontController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorGesOdontController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorGesOdontController@reyno')->name('reyno');
            });

            Route::prefix('sembrando_sonrisas')->as('sembrando_sonrisas.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorSembrandoSonrisasController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorSembrandoSonrisasController@aps')->name('aps');
                Route::get('/servicio', 'Indicators\_2020\IndicatorSembrandoSonrisasController@servicio')->name('servicio');
            });

            Route::prefix('mejoramiento_atencion_odontologica')->as('mejoramiento_atencion_odontologica.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorMejorAtenOdontController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorMejorAtenOdontController@aps')->name('aps');
            });

            Route::prefix('odontologico_integral')->as('odontologico_integral.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorOdontIntegralController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorOdontIntegralController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorOdontIntegralController@reyno')->name('reyno');
            });

            Route::prefix('resolutividad')->as('resolutividad.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorResolutividadController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorResolutividadController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorResolutividadController@reyno')->name('reyno');
            });

            Route::prefix('pespi')->as('pespi.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorPespiController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorPespiController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorPespiController@reyno')->name('reyno');
            });

            Route::prefix('equidad_rural')->as('equidad_rural.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorEquidadRuralController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorEquidadRuralController@aps')->name('aps');
                // Route::get('/reyno', 'Indicators\_2020\IndicatorEquidadRuralController@reyno')->name('reyno');
            });

            Route::prefix('respiratorio')->as('respiratorio.')->group(function(){
                Route::get('/', 'Indicators\_2020\IndicatorRespiratorioController@index')->name('index');

                Route::get('/aps', 'Indicators\_2020\IndicatorRespiratorioController@aps')->name('aps');
                Route::get('/reyno', 'Indicators\_2020\IndicatorRespiratorioController@reyno')->name('reyno');
            });
        });
    });

    Route::prefix('iaaps')->as('iaaps.')->group(function(){
        Route::get('/', function () { return view('indicators.iaaps.index'); })
            ->name('index');

        Route::prefix('2019')->as('2019.')->group(function(){
            Route::get('/', 'Indicators\IAAPS\_2019\IAAPSController@index')
                ->name('index');

            /* Iquique 1101 */
            Route::get('/{comuna}', 'Indicators\IAAPS\_2019\IAAPSController@show')
                ->name('show');

        });
    });

    Route::prefix('rems')->as('rems.')->group(function(){
        Route::get('/', 'Indicators\Rems\RemController@index')->name('index');
        Route::get('2019', function () { return view('indicators.rem.2019.index'); })->name('2019.index');
        Route::get('2020', function () { return view('indicators.rem.2020.index'); })->name('2020.index');

        Route::get('/{year}/{serie}', 'Indicators\Rems\RemController@index_serie_year')->name('year.serie.index');

        Route::get('/{year}/{serie}/{nserie}', 'Indicators\Rems\RemController@a01')->name('year.serie.nserie.index');
        Route::post('/{year}/{serie}/{nserie}', 'Indicators\Rems\RemController@show')->name('year.serie.nserie.index');

    });

});

Route::prefix('drugs')->as('drugs.')->middleware('auth')->group(function(){
    //fixme convertir a gets, put, delete
    Route::resource('courts','Drugs\CourtController');
    Route::resource('police_units','Drugs\PoliceUnitController');
    Route::resource('substances','Drugs\SubstanceController');

    Route::get('receptions/report','Drugs\ReceptionController@report')->name('receptions.report');
    Route::get('receptions/{reception}/record','Drugs\ReceptionController@showRecord')->name('receptions.record');

    Route::get('receptions/{receptionitem}/edit_item','Drugs\ReceptionController@editItem')->name('receptions.edit_item');
    Route::put('receptions/{receptionitem}/update_item','Drugs\ReceptionController@updateItem')->name('receptions.update_item');
    Route::delete('receptions/{receptionitem}/destroy_item','Drugs\ReceptionController@destroyItem')->name('receptions.destroy_item');
    Route::put('receptions/{receptionitem}/store_protocol','Drugs\ReceptionController@storeProtocol')->name('receptions.store_protocol');

    Route::get('receptions/{reception}/sample_to_isp','Drugs\SampleToIspController@show')->name('receptions.sample_to_isp.show');
    Route::get('receptions/{reception}/record_to_court','Drugs\RecordToCourtController@show')->name('receptions.record_to_court.show');


    Route::post('receptions/{reception}/item','Drugs\ReceptionController@storeItem')->name('receptions.storeitem');
    Route::post('receptions/{reception}/sample_to_isp','Drugs\SampleToIspController@store')->name('receptions.sample_to_isp.store');
    Route::post('receptions/{reception}/record_to_court','Drugs\RecordToCourtController@store')->name('receptions.record_to_court.store');

    Route::get('receptions/', 'Drugs\ReceptionController@index')->name('receptions.index');
    Route::get('receptions/create', 'Drugs\ReceptionController@create')->name('receptions.create');
    Route::get('receptions/show/{reception}', 'Drugs\ReceptionController@show')->name('receptions.show');
    Route::post('receptions/store', 'Drugs\ReceptionController@store')->name('receptions.store');
    Route::get('receptions/edit/{reception}', 'Drugs\ReceptionController@edit')->name('receptions.edit');
    Route::put('receptions/update/{reception}', 'Drugs\ReceptionController@update')->name('receptions.update');
//    Route::resource('receptions','Drugs\ReceptionController');
});

/* Bodega de Farmacia */
Route::prefix('pharmacies')->as('pharmacies.')->middleware('auth')->group(function(){
    Route::get('/', 'Pharmacies\PharmacyController@index')->name('index');
    Route::resource('establishments','Pharmacies\EstablishmentController');
    Route::resource('programs','Pharmacies\ProgramController');
    Route::resource('suppliers','Pharmacies\SupplierController');

    Route::prefix('products')->as('products.')->middleware('auth')->group(function(){
        Route::resource('receiving','Pharmacies\ReceivingController');
        Route::resource('receiving_item','Pharmacies\ReceivingItemController');
        Route::get('receiving/record/{receiving}','Pharmacies\ReceivingController@record')->name('receiving.record');
        Route::get('dispatch/product/due_date/{product_id?}','Pharmacies\DispatchController@getFromProduct_due_date')->name('dispatch.product.due_date')->middleware('auth');
        Route::get('dispatch/product/batch/{product_id?}/{due_date?}','Pharmacies\DispatchController@getFromProduct_batch')->name('dispatch.product.batch')->middleware('auth');
        Route::get('dispatch/product/count/{product_id?}/{due_date?}/{batch?}','Pharmacies\DispatchController@getFromProduct_count')->name('dispatch.product.count')->middleware('auth');
        Route::get('/exportExcel','Pharmacies\DispatchController@exportExcel')->name('exportExcel')->middleware('auth');

        Route::resource('dispatch','Pharmacies\DispatchController');
        Route::resource('dispatch_item','Pharmacies\DispatchItemController');
        Route::get('dispatch/record/{dispatch}','Pharmacies\DispatchController@record')->name('dispatch.record');
        Route::get('dispatch/sendC19/{dispatch}','Pharmacies\DispatchController@sendC19')->name('dispatch.sendC19');
        Route::get('dispatch/deleteC19/{dispatch}','Pharmacies\DispatchController@deleteC19')->name('dispatch.deleteC19');
        Route::post('dispatch/{dispatch}/file','Pharmacies\DispatchController@storeFile')->name('dispatch.storeFile');
        Route::get('dispatch/{dispatch}/file','Pharmacies\DispatchController@openFile')->name('dispatch.openFile');
        Route::resource('purchase','Pharmacies\PurchaseController');
        Route::resource('purchase_item','Pharmacies\PurchaseItemController');
        Route::get('purchase/record/{purchase}','Pharmacies\PurchaseController@record')->name('purchase.record');

        Route::resource('transfer','Pharmacies\TransferController');
        Route::get('transfer/{establishment}/auth', 'Pharmacies\TransferController@auth')->name('transfer.auth');
        Route::resource('deliver','Pharmacies\DeliverController');
        Route::put('deliver/{deliver}/confirm', 'Pharmacies\DeliverController@confirm')->name('deliver.confirm');
    });
    Route::resource('products','Pharmacies\ProductController');

    Route::prefix('reports')->as('reports.')->middleware('auth')->group(function(){
        Route::get('bincard','Pharmacies\ProductController@repBincard')->name('bincard');
        Route::get('purchase_report','Pharmacies\ProductController@repPurchases')->name('purchase_report');
        Route::get('informe_movimientos','Pharmacies\ProductController@repInformeMovimientos')->name('informe_movimientos');
        Route::get('product_last_prices','Pharmacies\ProductController@repProductLastPrices')->name('product_last_prices');
        Route::get('consume_history','Pharmacies\ProductController@repConsumeHistory')->name('consume_history');

        Route::get('products','Pharmacies\ProductController@repProduct')->name('products');
    });


});
