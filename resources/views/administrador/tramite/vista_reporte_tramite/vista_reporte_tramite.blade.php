@extends('principal')
@section('titulo', 'VISTA REPORTES')
@section('contenido')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">:::::::: VISTA REPORTES :::::::: </h5>
        </div>
    </div>


    <table border="1">
        <thead>
            <tr>
                <th>CITE</th>
                <th>REFERENCIA</th>
                <th>FECHA CREADA</th>
                <th>NOMBRE</th>
                <th>Nº (Recividos)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tramitesRealizados as $tramite)
                @if(isset($conteoRutasAgrupadas[$tramite->id]) && count($conteoRutasAgrupadas[$tramite->id]) > 0)
                    @foreach($conteoRutasAgrupadas[$tramite->id] as $destinatarioId => $count)
                        <tr>
                            <td>{{ $tramite->cite_texto }}</td>
                            <td>{{ $tramite->referencia }}</td>
                            <td>{{ $tramite->fecha_creada }}</td>
                            <td>
                                @php
                                    $cargo = "";
                                    if($tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->cargo_sm != null){
                                        $cargo =  $tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->cargo_sm->nombre;
                                    }else{
                                        $cargo =  $tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->cargo_mae->nombre;
                                    }
                                @endphp

                                {{  ' ( '.$cargo.' )   '.$tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->contrato->grado_academico->abreviatura.' '.$tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->persona->nombres.' '.$tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->persona->ap_paterno.' '.$tramite->hojas_ruta->firstWhere('destinatario_id', $destinatarioId)->destinatario_user->persona->ap_materno ?? 'N/A' }}
                            </td>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $tramite->cite_texto }}</td>
                        <td>{{ $tramite->referencia }}</td>
                        <td>{{ $tramite->fecha_creada }}</td>
                        <td>No hay hojas de ruta para este trámite.</td>
                        <td>0</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>


    {{ $tramites_hojas_ruta }}


@endsection
@section('scripts')
    <script>

    </script>
@endsection
