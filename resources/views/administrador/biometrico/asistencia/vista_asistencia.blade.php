@extends('principal')
@section('titulo', ' | GENERAR ASISTENCIA')
@section('estilos')
@endsection
@section('contenido')
    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-6">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                            <div>
                                <h5 class="mb-2">NOMBRES Y APELLIDOS</h5>
                                <p class="mb-0"><span class="text-muted me-2">{{ $persona->nombres.' '.$persona->ap_paterno.' '.$persona->ap_materno  }}</span></p>

                                <h5 class="mb-2 py-2">CI</h5>
                                <p class="mb-0"><span class="text-muted me-2">{{ $persona->ci  }}</span></p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-smart-home text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4">
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                            <div>
                                @if ($persona->contrato[0]->cargo_sm)
                                    <h6 class="mb-2">CARGO :
                                        {{ $persona->contrato[0]->cargo_sm->nombre }}
                                    </h6>
                                    <h6 class="mb-2">UNIDAD ADMINISTRATIVA | JEFATURA :
                                        @if (!empty($persona->contrato[0]->cargo_sm->unidades_admnistrativas->nombre))
                                            {{ $persona->contrato[0]->cargo_sm->unidades_admnistrativas->nombre }}
                                        @endif
                                    </h6>
                                    <h6 class="mb-2">DIRECCIÓN :
                                        {{ $persona->contrato[0]->cargo_sm->direccion->nombre }}
                                    </h6>
                                    <h6 class="mb-2">SECRETARIA MUNICIPAL :
                                        {{ '('.$persona->contrato[0]->cargo_sm->direccion->secretaria_municipal->sigla.') '.$persona->contrato[0]->cargo_sm->direccion->secretaria_municipal->nombre }}
                                    </h6>
                                @else
                                    <h6 class="mb-2">CARGO :
                                        {{ $persona->contrato[0]->cargo_mae->nombre }}
                                    </h6>
                                    <h6 class="mb-2">UNIDAD :
                                        {{ $persona->contrato[0]->cargo_mae->unidad_mae->descripcion }}
                                    </h6>
                                @endif
                                <p class="mb-0">
                                    <span class="text-muted me-2">
                                    </span>
                                </p>
                            </div>
                            <span class="avatar p-2 me-lg-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-device-laptop text-body"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    {{-- <div class="col-sm-6 col-lg-6">
                        <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                            <div>
                                <h6 class="mb-2">Discount</h6>
                                <h4 class="mb-2">$14,235.12</h4>
                                <p class="mb-0 text-muted">6k orders</p>
                            </div>
                            <span class="avatar p-2 me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-gift text-body"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-2">Affiliate</h6>
                                <h4 class="mb-2">$8,345.23</h4>
                                <p class="mb-0"><span class="text-muted me-2">150 orders</span><span
                                        class="badge bg-label-danger">-3.5%</span></p>
                            </div>
                            <span class="avatar p-2">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti-md ti ti-wallet text-body"></i></span>
                            </span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">:::::::: LISTAR ASISTENCIA :::::::: </h5>
        </div>





        <div class="table-responsive text-nowrap p-2">
            <div class="row text-center">
                <div class="col-sm-12 col-md-6 col-xl-6 col-lg-6">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        FECHA INICIAL : <strong>
                            {{ mb_strtoupper(fecha_literal($fecha_inicial->fecha, 5), 'UTF-8') }}</strong>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-xl-6 col-lg-6">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        FECHA FINAL : <strong> {{ mb_strtoupper(fecha_literal($fecha_final->fecha, 5), 'UTF-8') }}</strong>
                    </div>
                </div>
            </div>


            <table class="table table-hover" id="tabla_excepcion_horario" style="width: 100%;">
                <thead class="table-dark">
                    <tr>
                        {{-- <th>Nº</th> --}}
                        <th>DIA</th>
                        <th>FECHA</th>
                        <th>ENTRADA MAÑANA</th>
                        <th>SALIDA MAÑANA</th>
                        <th>ENTRADA TARDE</th>
                        <th>SALIDA TARDE</th>
                        <th>USUARIO</th>
                        <th>USUARIO EDITADO</th>
                        <th>OBSERVACIÓN</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        //$contar     = 1;
                        $minutos = 0;
                        $faltas = 0;
                        $faltas_contar = 0;
                        $retrasos_contar = 0;
                    @endphp
                    @foreach ($listar_biometrico as $biometricos)
                        @foreach ($biometricos as $lis)
                            <tr>
                                {{-- <td>{{ $contar++ }}</td> --}}
                                <td>{{ $lis->fecha_principal->dias_semana->nombre }}</td>
                                <td>{{ fecha_literal($lis->fecha_principal->fecha, 4) }}</td>

                                @if ($lis->fecha_principal->feriado == '')
                                    <td>
                                        @if ($lis->hora_ingreso_ma != '' && $lis->hora_ingreso_ma != null)
                                            @php
                                                // Asignar variables fuera de las condiciones para evitar redundancia
                                                $horario_primero = $lis->contrato->horario->rango_hora[0];
                                                $tolerancia_primero = $horario_primero->tolerancia;
                                                $hora_final_primero = $horario_primero->hora_final;
                                                //se suma la hora final de entrada mas tolerancia
                                                $sumado_primero = sumar_horas($hora_final_primero, $tolerancia_primero);
                                                // Verificar si hay una excepción de horario
                                                if (
                                                    !$lis->contrato->horario->rango_hora[0]->excepcion_horario->isEmpty()
                                                ) {
                                                    $fecha_ini =
                                                        $lis->contrato->horario->rango_hora[0]->excepcion_horario[0]
                                                            ->fecha_inicio;
                                                    $fecha_fin =
                                                        $lis->contrato->horario->rango_hora[0]->excepcion_horario[0]
                                                            ->fecha_final;

                                                    // Verificar si la fecha principal está dentro del rango de fechas de la excepción
                                                    if (
                                                        $lis->fecha_principal->fecha >= $fecha_ini &&
                                                        $lis->fecha_principal->fecha <= $fecha_fin
                                                    ) {
                                                        // Calcular el arreglo $valor_arr solo si es necesario
                                                        $valor_arr = [];
                                                        foreach (
                                                            $lis->contrato->horario->rango_hora[0]->excepcion_horario
                                                            as $lis1
                                                        ) {
                                                            foreach ($lis1->dias_semana_excepcion as $lxi) {
                                                                $valor_arr[] = $lxi->pivot->id_dias_sem;
                                                            }
                                                        }
                                                        // Verificar si el día de la semana está en la excepción de horario
                                                        if (
                                                            in_array($lis->fecha_principal->dias_semana->id, $valor_arr)
                                                        ) {
                                                            // Asignar los valores correspondientes a la excepción de horario
                                                            $hora_final_primero =
                                                                $lis->contrato->horario->rango_hora[0]
                                                                    ->excepcion_horario[0]->hora;
                                                            $sumado_primero = sumar_horas(
                                                                $hora_final_primero,
                                                                $tolerancia_primero,
                                                            );
                                                        }
                                                    }
                                                }
                                            @endphp

                                            <!--Fusionar bloques con la misma operación -->
                                            @if ($lis->hora_ingreso_ma > $sumado_primero)
                                                {{ $lis->hora_ingreso_ma }}
                                                @php
                                                    $minutos = sumar_tiempo(
                                                        $minutos,
                                                        contar_minutos_segundos($lis->hora_ingreso_ma, $sumado_primero),
                                                    );
                                                @endphp
                                                <!-- RECUERDA QUE ESTO ES PARA LOS RETRASOS EFECTUADOS -->
                                                <p style="color:red; font-size: 12px">
                                                    {{ horaen_palabras(contar_minutos_segundos($lis->hora_ingreso_ma, $sumado_primero)) }}
                                                </p>
                                            @else
                                                {{ $lis->hora_ingreso_ma }}
                                            @endif
                                        @else
                                            FALTA
                                            @php
                                                $faltas = $faltas + 1;
                                            @endphp
                                        @endif

                                    </td>
                                    <td>
                                        @if ($lis->hora_salida_ma != '' && $lis->hora_salida_ma != null)
                                            @if ($lis->hora_salida_ma == '00:00:00')

                                            @else
                                                {{ $lis->hora_salida_ma }}
                                            @endif
                                        @else
                                            FALTA
                                            @php
                                                $faltas = $faltas + 1;
                                            @endphp
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lis->hora_entrada_ta != '' && $lis->hora_entrada_ta != null)
                                            @php
                                                // Asignar variables fuera de las condiciones para evitar redundancia
                                                $horario_segundo = $lis->contrato->horario->rango_hora[2];
                                                $tolerancia_segundo = $horario_segundo->tolerancia;
                                                $hora_final_segundo = $horario_segundo->hora_final;
                                                $sumado_segundo = sumar_horas($hora_final_segundo, $tolerancia_segundo);

                                                // Verificar si la colección excepcion_horario está vacía usando el método isEmpty() NEGADA
                                                if (!$horario_segundo->excepcion_horario->isEmpty()) {
                                                    //echo "La colección excepcion_horario NO ESTA vacía.";
                                                    $fecha_ini_segundo =
                                                        $lis->contrato->horario->rango_hora[2]->excepcion_horario[0]
                                                            ->fecha_inicio;
                                                    $fecha_fin_segundo =
                                                        $lis->contrato->horario->rango_hora[2]->excepcion_horario[0]
                                                            ->fecha_final;

                                                    // Verificar si la fecha principal está dentro del rango de fechas de la excepción
                                                    if (
                                                        $lis->fecha_principal->fecha >= $fecha_ini_segundo &&
                                                        $lis->fecha_principal->fecha <= $fecha_fin_segundo
                                                    ) {
                                                        // Calcular el arreglo $valor_arr_segundo solo si es necesario
                                                        $valor_arr_segundo = [];
                                                        foreach (
                                                            $lis->contrato->horario->rango_hora[0]->excepcion_horario
                                                            as $lis1
                                                        ) {
                                                            foreach ($lis1->dias_semana_excepcion as $lxi) {
                                                                $valor_arr_segundo[] = $lxi->pivot->id_dias_sem;
                                                            }
                                                        }
                                                        // Verificar si el día de la semana está en la excepción de horario
                                                        if (
                                                            in_array(
                                                                $lis->fecha_principal->dias_semana->id,
                                                                $valor_arr_segundo,
                                                            )
                                                        ) {
                                                            // Asignar los valores correspondientes a la excepción de horario
                                                            $hora_final_segundo =
                                                                $lis->contrato->horario->rango_hora[0]
                                                                    ->excepcion_horario[0]->hora;
                                                            $sumado_segundo = sumar_horas(
                                                                $hora_final_segundo,
                                                                $tolerancia_segundo,
                                                            );
                                                        }
                                                    }
                                                }
                                            @endphp

                                            {{-- Fusionar bloques con la misma operación --}}
                                            @if ($lis->hora_entrada_ta > $sumado_segundo)
                                                {{ $lis->hora_entrada_ta }}
                                                @php
                                                    $minutos = sumar_tiempo(
                                                        $minutos,
                                                        contar_minutos_segundos($lis->hora_entrada_ta, $sumado_segundo),
                                                    );
                                                @endphp
                                                {{ horaen_palabras(contar_minutos_segundos($lis->hora_entrada_ta, $sumado_segundo)) }}
                                            @else
                                                @if ($lis->hora_entrada_ta == '00:00:00')

                                                @else
                                                    {{ $lis->hora_entrada_ta }}
                                                @endif
                                            @endif
                                        @else
                                            FALTA
                                            @php
                                                $faltas = $faltas + 1;
                                            @endphp
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lis->hora_salida_ta != '' && $lis->hora_salida_ta != null)
                                            {{ $lis->hora_salida_ta }}
                                        @else
                                            FALTA
                                            @php
                                                $faltas = $faltas + 1;
                                            @endphp
                                        @endif
                                    </td>
                                @else
                                    <td colspan="4"> {{ $lis->fecha_principal->feriado->descripcion }} </td>
                                @endif
                                <td>
                                    {{ $lis->usuario->nombres }}
                                </td>
                                <td>
                                    @if ($lis->id_user_up != null)
                                        {{ $lis->usuario_edit->nombres }}
                                    @endif
                                </td>
                                <td>
                                    @if ($lis->hora_entrada_ta == '00:00:00' && $lis->hora_salida_ma == '00:00:00' )
                                        HORARIO CONTINUO
                                    @endif
                                </td>
                                <td>
                                    <div class="d-inline-block tex-nowrap">
                                        @can('asistencia_editar')
                                            <button class="btn btn-sm btn-icon" onclick="editar_biometrico('{{ $lis->id }}')" type="button">
                                                <i class="ti ti-edit" ></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">FALTAS : {{ $faltas }}</th>
                        <th colspan="4">MINUTOS : {{ $minutos }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>




    <!-- Update horario Modal -->
    <div class="modal fade" id="modal_editar_biometrico" aria-hidden="true" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h4 class="mb-2">Modificación de biometrico </h4>
                        <span id="g_span" style="font-size: 13px;" >*</span>Se responsabiliza de la modificación<span id="g_span" >*</span>
                    </div>
                    <form id="formulario_editar_biometrico" class="row" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id_biometrico" id="id_biometrico">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="salida_maniana">Hora de salida mañana</label>
                            <input type="text" id="salida_maniana" name="salida_maniana" class="form-control uppercase-input"
                                placeholder="Ingrese la hora 13:00:00" maxlength="8" autofocus />
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="salida_tarde">Hora de salida tarde</label>
                            <input type="text" id="salida_tarde" name="salida_tarde" class="form-control uppercase-input"
                            placeholder="Ingrese la hora 23:59:59" maxlength="8" autofocus />
                        </div>
                    </form>
                    <div class="col-12 text-center demo-vertical-spacing">
                        <button id="btn_guardar_editar_biometrico" class="btn btn-primary me-sm-3 me-1">Guardar</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Update horario Modal -->



@endsection
@section('scripts')
    <script>


        //para guardar los registros a la base de datos
        async function editar_biometrico(id){
            try {
                let respuesta = await fetch("{{ route('asit_editar') }}",{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({id:id})
                });
                let dato = await respuesta.json();
                console.log(dato);
                if(dato.tipo==='success'){
                    $('#modal_editar_biometrico').modal('show');
                    document.getElementById('id_biometrico').value      = dato.mensaje.id;
                    document.getElementById('salida_maniana').value     = dato.mensaje.hora_salida_ma;
                    document.getElementById('salida_tarde').value       = dato.mensaje.hora_salida_ta;
                }
                if(dato.tipo==='error'){
                    alerta_top(dato.tipo, dato.mensaje);
                }
            } catch (error) {
                console.log('Error :'. error);
            }
        }


        //PARA GUARDAR EL EDITADO
        let btn_guardar_edit_biometrico     = document.getElementById("btn_guardar_editar_biometrico");
        let form_edit_biometrico            = document.getElementById("formulario_editar_biometrico");
        btn_guardar_edit_biometrico.addEventListener('click', async()=>{
            let datos = Object.fromEntries(new FormData(form_edit_biometrico).entries());
            validar_boton(true, 'Guardando . . . ', 'btn_guardar_editar_biometrico');
            try {
                let respuesta = await fetch("{{ route('asit_editar_save') }}",{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(datos)
                });
                let dato = await respuesta.json();
                if(dato.tipo==='success'){
                    alerta_top(dato.tipo, dato.mensaje);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                    validar_boton(false, 'Guardar ', 'btn_guardar_editar_biometrico');
                }
                if(dato.tipo==='error'){
                    alerta_top(dato.tipo, dato.mensaje);
                    validar_boton(false, 'Guardar ', 'btn_guardar_editar_biometrico');
                }
            } catch (error) {
                console.log('Error : '+error);
                validar_boton(false, 'Guardar ', 'btn_guardar_editar_biometrico');
            }
        });
    </script>
@endsection
