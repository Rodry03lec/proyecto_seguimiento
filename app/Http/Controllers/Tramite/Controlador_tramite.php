<?php

namespace App\Http\Controllers\Tramite;

use App\Http\Controllers\Controller;
use App\Models\Configuracion_tramite\User_cargo_tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function tramite_cargo($id){
        $id_descript = desencriptar($id);
        $cargo = User_cargo_tramite::with(['cargo_sm', 'cargo_mae'])->find($id_descript);
        $data['cargo_enum'] = $cargo;

        $data['menu'] = 60;
        return view('administrador.tramite.correspondencia.correspondencia', $data);
    }
}
