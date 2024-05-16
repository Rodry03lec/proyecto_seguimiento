<?php

namespace App\Http\Controllers\Biometrico;

use App\Http\Controllers\Controller;
use App\Models\Biometrico\Biometrico;
use App\Models\Fechas\Dias_semana;
use App\Models\Fechas\Fecha_principal;
use App\Models\Registro\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Controlador_asistencias extends Controller
{
    /**
     * @version 1.0
     * @author  Rodrigo Lecoña Quispe <rodrigolecona03@gmail.com>
     * @param Controlador Administrar la parte de VER O MODIFICAR LAS ASISTENCIAS OJO SOLO PARA PERSONAL AUTORIZADO
     * ¡Muchas gracias por preferirnos! Esperamos poder servirte nuevamente
     */

    //PARA LA PARTE DE LA ADMINISTRACION DE LAS ASISTENCIAS
    public function asistencia(){
        $data['menu'] = '17';
        $data['listar_dias'] = Dias_semana::OrderBy('id', 'asc')->get();
        return view('administrador.biometrico.asistencia.listar_asistencia', $data);
    }

    //PARA GENERAR LA ASISTENCIA
    public function generar_asistencia(Request $request){
        $data['menu']               = '17';
        $data['ci_persona']         = $request->ci;
        $data['fecha_inicio']       = $request->fecha_inicial;
        $data['fecha_final']        = $request->fecha_final;
        $data['dias']               = $request->dias;

        //primero verificamos que persona es?
        $persona = Persona::where('ci', $request->ci)->first();
        //identificamos la fecha inicial el la tabla principal
        $fecha_incial_emp = Fecha_principal::where('fecha', $request->fecha_inicial)->first();
        //identificamos la fecha fianl de la tabla principal
        $fecha_final_emp = Fecha_principal::where('fecha', $request->fecha_final)->first();

        //vamos a enviar para ver los datos
        $data['fecha_inicial']  = $fecha_incial_emp;
        $data['fecha_final']    = $fecha_final_emp;

        //aqui listamos el biometrico
        //$biometrico = Biometrico::where('id_persona', $persona->id)->get();
        //dd($request->dias);

        $biometricos_lis = [];

        $fecha_principal_listar = Fecha_principal::with(['dias_semana'])
            ->where('id', '>=', $fecha_incial_emp->id)
            ->where('id', '<=', $fecha_final_emp->id)
            ->get();

        //dd($fecha_principal_listar);

        foreach ($fecha_principal_listar as $lis) {
            if (in_array($lis->id_dia_sem, $request->dias)) {
                $biometrico = Biometrico::with(['usuario', 'usuario_edit', 'fecha_principal'=>function($fp1){
                    $fp1->with(['dias_semana', 'feriado']);
                },'contrato'=>function($c1){
                    $c1->with(['horario'=>function($h1){
                        $h1->with(['rango_hora'=>function($rh1){
                            $rh1->with(['excepcion_horario'=>function($exh1){
                                $exh1->with(['dias_semana_excepcion']);
                            },'horarios']);
                        }]);
                    }]);
                }])->where('id_persona', $persona->id)->where('id_fecha',  $lis->id)->get();
                // Agrega los resultados al array $biometricos
                //if(!$biometrico->isEmpty()){
                    $biometricos_lis[] = $biometrico;
                //}
                //echo $biometrico. "\n";
            }
        }

        //dd($biometricos_lis);
        $data['listar_biometrico']  = $biometricos_lis;
        $data['persona']            = Persona::with(['contrato'=>function($co){
            $co->with(['profesion', 'grado_academico', 'cargo_sm'=>function($cs){
                $cs->with(['unidades_admnistrativas', 'direccion'=>function($dir){
                    $dir->with(['secretaria_municipal']);
                }]);
            }, 'cargo_mae'=>function($cm){
                $cm->with(['unidad_mae']);
            }]);
            $co->where('estado', 'activo');
        }])->find($persona->id);

        return view('administrador.biometrico.asistencia.vista_asistencia', $data);
    }

    //para editar la parte de las asistenciass
    public function editar_asistencia(Request $request){
        try {
            $biometrico = Biometrico::find($request->id);
            if($biometrico){
                $data = mensaje_mostrar('success', $biometrico);
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al editar los datos');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error al editar los datos');
        }
        return response()->json($data);
    }

    //para guardar lo editado de la asistencia
    public function guardar_asist_editado(Request $request){
        try {
            $biometrico_asistencia                  = Biometrico::find($request->id_biometrico);
            $biometrico_asistencia->hora_salida_ma  = $request->salida_maniana;
            $biometrico_asistencia->hora_salida_ta  = $request->salida_tarde;
            $biometrico_asistencia->id_user_up      = Auth::user()->id;
            $biometrico_asistencia->save();
            if($biometrico_asistencia->id){
                $data = mensaje_mostrar('success', 'Se guardo con exito el registro');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al insertar los datos');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error al insertar los datos');
        }
        return response()->json($data);
    }

}
