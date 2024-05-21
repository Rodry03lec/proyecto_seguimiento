<?php

namespace App\Http\Controllers\Tramite;

use App\Http\Controllers\Controller;
use App\Models\Configuracion_tramite\Tipo_estado;
use App\Models\Configuracion_tramite\Tipo_tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controlador_configuracion extends Controller
{
    /**
     * PARA LA PARTE DEL TIPO DE TRAMITE
        */
    public function tipo_tramite(){
        $data['menu'] = '50';
        return view('administrador.tramite.configuracion_tramite.tipo_tramite', $data);
    }

    //para listar tosos los tramitess
    public function tipo_tramite_listar(){
        $tipo_tramite = Tipo_tramite::OrderBy('id', 'asc')->get();
        return response()->json($tipo_tramite);
    }

    //para guardar nuevo tramite
    public function tipo_tramite_nuevo(Request $request){
        $validar = Validator::make($request->all(),[
            'nombre'        => 'required|unique:rl_tipo_tramite,nombre',
            'sigla'         => 'required'
        ]);
        if($validar->fails()){
            $data = mensaje_mostrar('errores', $validar->errors());
        }else{
            $nuevo_tipo_tramite             = new Tipo_tramite();
            $nuevo_tipo_tramite->nombre     = $request->nombre;
            $nuevo_tipo_tramite->sigla      = $request->sigla;
            $nuevo_tipo_tramite->estado     = true;
            $nuevo_tipo_tramite->save();
            if($nuevo_tipo_tramite->id){
                $data = mensaje_mostrar('success', 'Se inserto con éxito');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al insertar !');
            }
        }
        return response()->json($data);
    }

    //para cambiar el estados
    public function  tipotramite_estado(Request $request){
        try {
            $tipo_tramite = Tipo_tramite::find($request->id);
            $tipo_tramite->estado = ($tipo_tramite->estado == true) ? false : true;
            $tipo_tramite->save();
            if($tipo_tramite->id){
                $data = mensaje_mostrar('success', 'Se cambio el estado!');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al cambiar el estado!');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error al cambiar el estado!');
        }
        return response()->json($data);
    }

    //para eliminar ele registros
    public function  tipotramite_eliminar(Request $request){
        try {
            $tipo_tramite = Tipo_tramite::find($request->id);
            if($tipo_tramite->delete()){
                $data = mensaje_mostrar('success', 'Se elimino con éxito!');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al cambiar el estado!');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un error al eliminar!');
        }
        return response()->json($data);
    }

    //para editar el tipo de tramite
    public function tipotramite_editar(Request $request) {
        try {
            $tipo_tramite = Tipo_tramite::find($request->id);
            if($tipo_tramite){
                $data = mensaje_mostrar('success', $tipo_tramite);
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un prblema al editar !');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un prblema al editar !');
        }
        return response()->json($data);
    }

    //para guardar el registro
    public function tipotramite_update(Request $request) {
        $validar = Validator::make($request->all(),[
            'nombre_'        => 'required|unique:rl_tipo_tramite,nombre,'.$request->id_tipotramite,
            'sigla_'         => 'required'
        ]);
        if($validar->fails()){
            $data = mensaje_mostrar('errores', $validar->errors());
        }else{
            $nuevo_tipo_tramite             = Tipo_tramite::find($request->id_tipotramite);
            $nuevo_tipo_tramite->nombre     = $request->nombre_;
            $nuevo_tipo_tramite->sigla      = $request->sigla_;
            $nuevo_tipo_tramite->save();
            if($nuevo_tipo_tramite->id){
                $data = mensaje_mostrar('success', 'Se editó con éxito');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al editar !');
            }
        }
        return response()->json($data);
    }

    /**
      * FIN DE LA PARTE DEL TIPO DE TRAMITE
    */


    /**
     * PARA LA PARTE DE LA ADMINISTRACION DEL LOS ESTADOS
     */
    public function tipo_estado_tramite(){
        $data['menu'] = '51';
        return view('administrador.tramite.configuracion_tramite.tipo_estado', $data);
    }
    //para el listado de los registross
    public function  tipo_estado_listar(){
        $tipo_estado = Tipo_estado::OrderBy('id', 'desc')->get();
        return response()->json($tipo_estado);
    }

    //para guardar el registro
    public function  tipo_estado_nuevo(Request $request){
        $validar = Validator::make($request->all(),[
            'color'        => 'required|unique:rl_tipo_estado,color',
            'nombre'         => 'required|unique:rl_tipo_estado,nombre'
        ]);

        if($validar->fails()){
            $data = mensaje_mostrar('errores', $validar->errors());
        }else{
            $tipo_estado             = new Tipo_estado();
            $tipo_estado->nombre     = $request->nombre;
            $tipo_estado->color      = $request->color;
            $tipo_estado->save();
            if($tipo_estado->id){
                $data = mensaje_mostrar('success', 'Se inserto con éxito');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al insertar !');
            }
        }
        return response()->json($data);

    }

    //para eliminar el registro
    public function tipo_estado_eliminar(Request $request){
        try {
            $tipo_estado = Tipo_estado::find($request->id);
            if($tipo_estado->delete()){
                $data = mensaje_mostrar('success', 'Se elimino el registro con éxito');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un problema al eliminar !');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un problema al eliminar !');
        }
        return response()->json($data);
    }

    //para la edicion del registro
    public function tipo_estado_editar(Request $request){
        try {
            $tipo_estado = Tipo_estado::find($request->id);
            if($tipo_estado){
                $data = mensaje_mostrar('success', $tipo_estado);
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un problema al editar');
            }
        } catch (\Throwable $th) {
            $data = mensaje_mostrar('error', 'Ocurrio un problema al editar');
        }
        return response()->json($data);
    }

    //para guardar lo editado
    public function tipo_estado_update(Request $request){
        $validar = Validator::make($request->all(),[
            'color_'        => 'required|unique:rl_tipo_estado,color,'.$request->id_tipoestado,
            'nombre_'         => 'required|unique:rl_tipo_estado,nombre,'.$request->id_tipoestado
        ]);

        if($validar->fails()){
            $data = mensaje_mostrar('errores', $validar->errors());
        }else{
            $tipo_estado             = Tipo_estado::find($request->id_tipoestado);
            $tipo_estado->nombre     = $request->nombre_;
            $tipo_estado->color      = $request->color_;
            $tipo_estado->save();
            if($tipo_estado->id){
                $data = mensaje_mostrar('success', 'Se editó con éxito');
            }else{
                $data = mensaje_mostrar('error', 'Ocurrio un error al editar !');
            }
        }
        return response()->json($data);
    }

    /**
     * FIN DE LA PARTE DE ADMINISTRACION DE LOS ESTADOS
     */
}
