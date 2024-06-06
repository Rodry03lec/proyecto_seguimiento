<?php

namespace App\Http\Controllers\Tramite;

use App\Http\Controllers\Controller;
use App\Models\Configuracion_tramite\Tipo_prioridad;
use App\Models\Configuracion_tramite\Tipo_tramite;
use App\Models\Configuracion_tramite\User_cargo_tramite;
use App\Models\Tramite\Hojas_ruta;
use App\Models\Tramite\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;

class Controlador_tramite extends Controller{
    //Para la administracion de los tramites
    public function  vizualizar_cargos_tramite() {
        $data['menu'] = 60;
        $data['listar_cargos'] = User_cargo_tramite::with(['cargo_sm','cargo_mae'])
                                            ->where('estado', true)
                                            ->where('id_usuario', Auth::user()->id)
                                            ->get();
        return view('administrador.tramite.vista_tramite', $data);
    }
    //para la administracion del tramite cargo
    /**
     * PARA LA PARTE DE LA CORRESPONDENCIA
     */
    public function tramite_cargo($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'CORRESPONDENCIA';
        $data['menu'] = 60;

        $data['tipo_tramite']       = Tipo_tramite::where('estado', 1)->get();
        $data['tipo_prioridad']     = Tipo_prioridad::get();
        $data['destinatario_ti']    = User_cargo_tramite::with(['cargo_sm', 'cargo_mae', 'usuario', 'persona'])->where('estado', 1)->get();

        return view('administrador.tramite.correspondencia.correspondencia', $data);

    }

    //Para la parte de tipo de correspondencia
    public function correspondencia_tipo_sigla(Request $request){
        try {
            $tipo_tramite       = Tipo_tramite::find($request->id);
            $user_cargo_tram    = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($request->id_user_cargo);
            if($tipo_tramite){

                $cargo_normal = "";
                if($user_cargo_tram->cargo_sm != null ){
                    $cargo_normal = abreviarCargo($user_cargo_tram->cargo_sm->nombre);
                }else{
                    $cargo_normal = abreviarCargo($user_cargo_tram->cargo_mae->nombre);
                }

                $data = [
                    'tipo'              => 'success',
                    'tipo_tramite'      => $tipo_tramite,
                    'user_cargo_tram'   => $user_cargo_tram,
                    'cargo_normal'      => $cargo_normal
                ];
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error');
        }
        return response()->json($data);
    }

    //para listar los tramites del cargo de la persona
    public function correspondencia_listar(Request $request){
        $listar_correspondencia = Tramite::with(['tipo_prioridad','tipo_tramite', 'estado_tipo', 'remitente_user'=>function ($ruse) {
            $ruse->with(['persona', 'contrato'=>function($cotn){
                $cotn->with(['grado_academico']);
            }]);
        }, 'destinatario_user'=>function($des_user){
            $des_user->with(['cargo_sm','cargo_mae','persona', 'contrato'=>function ($cdes) {
                $cdes->with(['grado_academico']);
            }]);
        }, 'user_cargo_tramite'=>function ($uct) {
            $uct->with(['cargo_sm','cargo_mae','persona', 'contrato'=>function($cto){
                $cto->with(['grado_academico']);
            }]);
        }])->where('user_cargo_id', $request->id)->OrderBy('id', 'desc')->get();
        return response()->json($listar_correspondencia);
    }

    //funcion para contar los tramites del año
    function contarTramitesPorAno($year) {
        // Cachea el conteo de trámites creados en el año especificado durante 60 segundos
        return Cache::remember("tramites_count_$year", 60, function () use ($year) {
            return Tramite::whereYear('fecha_creada', $year)->count();
        });
    }

    //para guardar el registro de nuvo tramite
    public function correspondencia_nuevo(Request $request) {
        $validar = Validator::make($request->all(), [
            'tipo_tramite'  => ['required', 'not_in:0'],
            'cite'          => 'required',
            'prioridad'     => ['required', 'not_in:0'],
            'referencia'    => 'required',
            'instructivo'   => 'required',
        ]);

        if ($validar->fails()) {
            $data = mensaje_mostrar('errores', $validar->errors());
        }else{
            try {
                // Inicia una transacción
                DB::beginTransaction();

                // Obtén la gestión actual
                $gestion = date('Y');

                // Obtener el último trámite del año en curso usando Eloquent sin usar `lockForUpdate`
                $ultimo_tramite = Tramite::whereYear('created_at', $gestion)
                    ->orderBy('id', 'desc')
                    ->first();

                $num_unico_tramite = $ultimo_tramite ? $ultimo_tramite->id + 1 : 1;
                $tramite_num = ($gestion % 100).$num_unico_tramite;

                $user_cargo_tram = User_cargo_tramite::with(['contrato' => function ($con) {
                    $con->with(['grado_academico']);
                }, 'usuario', 'persona', 'cargo_sm', 'cargo_mae'])->find($request->id_remitente);

                // Crear un nuevo trámite
                $tramite_nuevo                      = new Tramite();
                $tramite_nuevo->fecha_creada        = date('Y-m-d');
                $tramite_nuevo->hora_creada         = date('H:i:s');
                $tramite_nuevo->fecha_hora_creada   = date('Y-m-d H:i:s');
                $tramite_nuevo->cite                = $request->cite_numero;
                $tramite_nuevo->cite_texto          = $request->cite;
                $tramite_nuevo->numero_hojas        = $request->numero_hojas;
                $tramite_nuevo->numero_anexos       = $request->numero_anexos;
                $tramite_nuevo->referencia          = $request->referencia;

                if ($request->remitente_txt) {
                    $tramite_nuevo->remitente_nombre    = $request->remitente_txt;
                    $tramite_nuevo->remitente_cargo     = $request->cargo_txt;
                    $tramite_nuevo->remitente_txt       = $request->remitente_txt;
                }

                $tramite_nuevo->destinatario_nombre = $user_cargo_tram->contrato->grado_academico->abreviatura . ' ' . $user_cargo_tram->persona->nombres . ' ' . $user_cargo_tram->persona->ap_paterno . ' ' . $user_cargo_tram->persona->ap_paterno;

                if ($user_cargo_tram->cargo_sm != null) {
                    $tramite_nuevo->destinatario_cargo = $user_cargo_tram->cargo_sm->nombre;
                    $tramite_nuevo->destinatario_sigla = abreviarCargo_tres($user_cargo_tram->cargo_sm->nombre);
                } else {
                    $tramite_nuevo->destinatario_cargo = $user_cargo_tram->cargo_mae->nombre;
                    $tramite_nuevo->destinatario_sigla = abreviarCargo_tres($user_cargo_tram->cargo_mae->nombre);
                }

                $tramite_nuevo->gestion = $gestion;
                $tramite_nuevo->numero_unico = $tramite_num;
                $tramite_nuevo->codigo = Str::random(10);
                $tramite_nuevo->id_tipo_prioridad = $request->prioridad;
                $tramite_nuevo->id_tipo_tramite = $request->tipo_tramite;
                $tramite_nuevo->id_estado = 2;
                $tramite_nuevo->remitente_id = $request->id_remitente;
                $tramite_nuevo->destinatario_id = $request->destinatario;
                $tramite_nuevo->user_cargo_id = $request->id_remitente;
                $tramite_nuevo->save();

                // Guardar las hojas de ruta
                $hoja_ruta = new Hojas_ruta();
                $hoja_ruta->paso = 1;
                $hoja_ruta->paso_txt = numero_a_ordinal(1);
                $hoja_ruta->instructivo = $request->instructivo;
                $hoja_ruta->nro_hojas_ingreso = $request->numero_hojas;
                $hoja_ruta->nro_anexos_ingreso = $request->numero_anexos;
                $hoja_ruta->fecha_ingreso = date('Y-m-d H:m:s');
                $hoja_ruta->fecha_salida = date('Y-m-d H:m:s');
                $hoja_ruta->fecha_envio = date('Y-m-d H:m:s');
                $hoja_ruta->actual = 1;
                $hoja_ruta->remitente_id = $request->id_remitente;
                $hoja_ruta->destinatario_id = $request->destinatario;
                $hoja_ruta->estado_id = 2;
                $hoja_ruta->tramite_id = $tramite_nuevo->id;
                $hoja_ruta->save();

                // Confirmar la transacción
                DB::commit();

                // Verifica si se guardó la hoja de ruta
                if ($hoja_ruta->id) {
                    $data = mensaje_mostrar('success', 'Se guardó con éxito!');
                } else {
                    $data = mensaje_mostrar('error', 'Error al crear el trámite');
                }

            } catch (\Exception $e) {
                // Revertir la transacción si algo falla
                DB::rollBack();
                $data = mensaje_mostrar('error', 'Error al crear el trámite: ' . $e->getMessage());
            }
        }
        return response()->json($data);
    }

    //para vizauliar la correspondencia
    public function correspondencia_vizualizar(Request $request){
        try {
            //esto es para el tramite
            $tramite = Tramite::with(['tipo_prioridad','tipo_tramite', 'estado_tipo', 'remitente_user'=>function ($ruse) {
            $ruse->with(['persona', 'contrato'=>function($cotn){
                $cotn->with(['grado_academico']);
            }]);
            }, 'destinatario_user'=>function($des_user){
                $des_user->with(['cargo_sm','cargo_mae','persona', 'contrato'=>function ($cdes) {
                    $cdes->with(['grado_academico']);
                }]);
            }, 'user_cargo_tramite'=>function ($uct) {
                $uct->with(['cargo_sm','cargo_mae','persona', 'contrato'=>function($cto){
                    $cto->with(['grado_academico']);
                }]);
            }])->find($request->id);
            //esto es para la hoja de ruta
            $listar_hojas_ruta = Hojas_ruta::with(['remitente_user'=>function($rem_user){
                $rem_user->with(['cargo_sm', 'cargo_mae', 'contrato'=>function($ru_con){
                    $ru_con->with(['grado_academico']);
                }, 'usuario', 'persona']);
            }, 'destinatario_user'=>function($des_user){
                $des_user->with(['cargo_sm', 'cargo_mae','contrato'=>function($de_con){
                    $de_con->with(['grado_academico']);
                }]);
            }, 'estado_tipo', 'tramite'])
                ->where('tramite_id', $request->id)->get();

            $data = [
                'tipo'      => 'success',
                'tramite'   => $tramite,
                'hoja_ruta' => $listar_hojas_ruta
            ];
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un problema');
        }
            return response()->json($data);
    }
    /**
     * FIN DE LA PARTE DE CORRESPONDENCIA
     */

    /**
     * PARA LA PARTE DE BANDEJA DE ENTRADA
     */
    //para la parte de la bandeja de entrada
    public function  bandeja_entrada($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'BANDEJA DE ENTRADA';
        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.bandeja_entrada', $data);
    }
    /**
     * FIN DE LA PARTE DE BANDEJA DE ENTRADA
     */

    /**
     * PARA LA PARTE DE LOS RECIVIDOS
     */
    public function recibidos($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'RECIBIDOS';
        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.recibidos', $data);
    }
    /**
     * FIN DE LA PARTE DE LOS RECIVIDOS
     */


    /**
     * PARA LA PARTE DE LOS ENVIADOS
     */
    public function enviados($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'RECIBIDOS';
        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.enviados', $data);
    }
    /**
     * FIN DE LA PARTE DE LOS ENVIADOS
     */


    /**
     * PARA LA PARTE DE LOS OBSERVADOS
     */
    public function observados($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'RECIBIDOS';
        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.observados', $data);
    }
    /**
     * FIN DE LA PARTE DE LOS OBSERVADOS
     */



    /**
      * PARA LA PARTE DE LOS ARCHIVADOS
    */
    public function archivados($id){
        $id_descript                = desencriptar($id);
        $cargo                      = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum']         = $cargo;
        $data['id_user_cargo_tram'] = $id_descript;
        $data['titulo_menu']        = 'ARCHIVADOS';
        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.archivados', $data);
    }
    /**
     * FIN DE LA PARTE DE LOS ARCHIVADOS
     */

}
